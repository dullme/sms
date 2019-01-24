<?php

namespace App\Http\Controllers;

use App\Card;
use App\Task;
use App\TaskHistory;
use App\Withdraw;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HomeController extends ResponseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function detail(Request $request)
    {
        $task_histories = TaskHistory::with(['task' => function ($query) {
            $query->select('id', 'content', 'amount');
        }])
            ->where('user_id', Auth()->user()->id);

        if ($request->input('status') == 'fail') {
            $task_histories = $task_histories->where('status', 0)->paginate(20);
        } else if ($request->input('status') == 'success') {
            $task_histories = $task_histories->where('status', 1)->paginate(20);
        } else {
            $task_histories = $task_histories->paginate(20);
        }

        $task_histories_count = TaskHistory::where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->where('user_id', Auth()->user()->id)
            ->select('id', 'status')
            ->get();
        $fail = $task_histories_count->where('status', 0)->count();
        $success = $task_histories_count->where('status', 1)->count();

        return view('detail', compact('task_histories', 'fail', 'success'));
    }

    public function infoEdit()
    {
        return view('infoEdit');
    }

    public function saveInfoEdit(Request $request)
    {
        $request->validate([
            'real_name'         => 'required',
            'bank'              => 'required',
            'bank_card_number'  => 'required',
            'withdraw_password' => 'required|min:6|max:20',
            'password'          => 'required'
        ]);

        $user = User::findOrFail(Auth()->user()->id);
        if (!Hash::check($request->input('password'), $user->password)) {
            Session::flash('editInfo', '修改失败，登陆密码错误！');
        } else {
            $user->real_name = $request->input('real_name');
            $user->bank = $request->input('bank');
            $user->bank_card_number = $request->input('bank_card_number');
            if ($user->withdraw_password != $request->input('withdraw_password')) {
                $user->withdraw_password = $request->input('withdraw_password');
                $user->withdraw_time = Carbon::now()->addDay();
            }
            $user->save();

            Session::flash('editInfo', '信息修改成功！');
        }

        return back();
    }

    public function infoWithdraw()
    {
        return view('infoWithdraw');
    }

    public function saveInfoWithdraw(Request $request)
    {
        $request->validate([
            'withdraw_amount'   => 'required|integer|hundred',
            'withdraw_password' => 'required',
        ]);
        $user = User::findOrFail(Auth()->user()->id);

        if ($request->input('withdraw_password') != $user->withdraw_password) {
            Session::flash('withdrawInfo', '资金密码错误！');

            return back();
        }

        if (!is_null($user->withdraw_time) && $user->withdraw_time > Carbon::now()) {
            Session::flash('withdrawInfo', "{$user->withdraw_time} 之前无法申请提现");

            return back();
        }

        $has_amount = intval($user->amount * 100) / 100;
        $amount = $request->input('withdraw_amount');

        if ($has_amount >= $amount) {
            DB::transaction(function () use ($user, $amount) {
                Withdraw::create([
                    'user_id'          => Auth()->user()->id,
                    'amount'           => $amount,
                    'status'           => 0,
                    'balance'          => $user->amount,
                    'bank_card_number' => $user->bank_card_number,
                    'bank'             => $user->bank,
                ]);
                $user->decrement('amount', $amount * 10000);
            });

            Session::flash('withdrawInfo', '提现成功！');
        } else {
            Session::flash('withdrawInfo', '提现失败！');
        }

        return back();
    }

    public function infoTransfer()
    {
        return view('infoTransfer');
    }

    public function saveInfoTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfer_amount'   => 'required|integer|hundred',
            'withdraw_password' => 'required',
            'username'          => 'required',
        ]);
        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $transfer_user = User::where('username', $request->input('username'))->first();
        if (!$transfer_user) {
            return $this->setStatusCode(422)->responseError('找不到该用户');
        }

        if ($transfer_user->id == Auth()->user()->id) {
            return $this->setStatusCode(422)->responseError('您不能给自己转转');
        }

        $user = User::findOrFail(Auth()->user()->id);

        if ($request->input('withdraw_password') != $user->withdraw_password) {
            return $this->setStatusCode(422)->responseError('资金密码错误');
        }

        if (!is_null($user->withdraw_time) && $user->withdraw_time > Carbon::now()) {
            return $this->setStatusCode(422)->responseError("{$user->withdraw_time} 之前无法申请提现");
        }

        $has_amount = intval($user->amount * 100) / 100;
        $amount = $request->input('transfer_amount');

        if ($has_amount >= $amount) {
            $res = DB::transaction(function () use ($user, $transfer_user, $amount) {
                //转出
                Withdraw::create([
                    'user_id'          => Auth()->user()->id,
                    'amount'           => $amount,
                    'status'           => 7,
                    'balance'          => $user->amount,
                    'bank_card_number' => $transfer_user->username,
                    'bank'             => '内部转出',
                    'remark'           => '成功',
                    'payment_at'       => Carbon::now(),
                ]);
                $user->decrement('amount', $amount * 10000);

                //转入
                Withdraw::create([
                    'user_id'          => $transfer_user->id,
                    'amount'           => $amount,
                    'status'           => 8,
                    'balance'          => $transfer_user->amount,
                    'bank_card_number' => $user->username,
                    'bank'             => '内部转入',
                    'remark'           => '成功',
                    'payment_at'       => Carbon::now(),
                ]);

                return $transfer_user->increment('amount', $amount * 10000);

            });

            if ($res) {
                return $this->responseSuccess("转账成功！");
            } else {
                return $this->setStatusCode(422)->responseError("转账失败");
            }
        } else {
            return $this->setStatusCode(422)->responseError("转账金额不足");
        }
    }

    public function infoTransaction()
    {
        $withdraw = Withdraw::where('user_id', Auth()->user()->id)->orderBy('id', 'DESC')->paginate(20);

        return view('infoTransaction', compact('withdraw'));
    }

    public function config()
    {
        return view('config');
    }

    public function saveConfig(Request $request)
    {
        $request->validate([
            'baud_rate'  => 'required',
            'mode'       => 'required',
            'encryption' => 'required',
        ]);

        $user = User::findOrFail(Auth()->user()->id);
        $user->baud_rate = $request->input('baud_rate');
        $user->one_day_max_send_count = $request->input('one_day_max_send_count');
        $user->mode = $request->input('mode');
        $user->encryption = $request->input('encryption');
        $res = $user->save();

        if ($res) {
            Session::flash('saveConfig', '保存成功！');
        } else {
            Session::flash('saveConfig', '保存失败！');
        }

        return back();
    }

    public function searchUser(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $username = $request->input('username');

        $user = User::where('username', $username)->select('id', 'username', 'real_name')->first();
        if (mb_strlen($user->real_name) == 2) {
            $user->real_name = '*' . mb_substr($user->real_name, -1);
        } else if (mb_strlen($user->real_name) > 2) {
            $ss = '';
            for ($i = 1; $i <= mb_strlen($user->real_name) - 2; $i++) {
                $ss .= '*';
            }
            $user->real_name = mb_substr($user->real_name, 0, 1) . $ss . mb_substr($user->real_name, -1);
        }

        if ($user) {
            return $this->responseSuccess($user);
        } else {
            return $this->setStatusCode(422)->responseError('找不到该用户');
        }
    }


    /**
     * 是否可以发送短信
     */
    public function canSend()
    {
        return $this->responseSuccess($this->thisTimeCanSend());
    }

    public function makeCard(Request $request)
    {
        $request->validate([
            'device' => 'required',
            'send'   => 'required'
        ]);

        $device = $request->input('device');

        $real_device = collect($device)->map(function ($device) {
            $base_status = collect($device['status']);
            $cards = Card::whereIn('name', $base_status->pluck('iccid'))->select('id', 'name', 'password', 'status')->get();
            $status = [];
            $add_amount_card = [];
            for ($i = 0; $i < $device['max-ports']; $i++) {
                for ($j = 0, $k = 0, $l = 0; $j < $device['max-slot']; $j++) {
                    $port = ($i + 1) . '.' . str_pad($j + 1, 2, '0', STR_PAD_LEFT);
                    $card = $base_status->where('port', $port)->first();
                    if ($card) {
                        if ($card['st'] == 0) {
                            $type = 'empty';    //st为0时表示没有卡
                        } else if ($card['st'] > 0 && ($card['iccid'] == '' || $card['imsi'] == '')) {
                            $type = 'failed';   //iccid和imsi其中有一个为空表示读卡失败 需要切卡后重新读取
                        } else {
                            $databases_card = $cards->where('name', $card['iccid'])->first();
                            if ($databases_card) {
                                if ($databases_card->password != $card['imsi'] || $databases_card->status == 1) {
                                    $type = 'wrong';    //匹配失败的卡
                                } else {
                                    $type = 'success';  //匹配成功的卡
                                }
                            } else {
                                $type = 'unknown';  //未知卡
                            }
                        }
                        $res = [
                            'port'     => $card['port'],
                            'iccid'    => $card['iccid'] ?? '',
                            'imsi'     => $card['imsi'] ?? '',
                            'st'       => $card['st'],
                            'has_card' => $card['st'] == 0 ? false : true,  //st为0时卡为空
                            'status'   => $type,
                        ];
                        $add_amount_card[] = array_merge($res, [
                            'ip'  => $device['ip'],
                            'mac' => $device['mac'],
                        ]);
                    } else {
                        $res = [
                            'port'     => $port,
                            'iccid'    => '',
                            'imsi'     => '',
                            'st'       => 0,
                            'has_card' => false,
                            'status'   => 'empty',
                        ];
                    }

                    if ($j % 2 != 0) {
                        $status[$i][0][$k] = $res;
                        $k++;
                    } else {
                        $status[$i][1][$l] = $res;
                        $l++;
                    }
                }

            }

            return [
                'ip'              => $device['ip'],
                'mac'             => $device['mac'],
                'income'          => Redis::get(Auth()->user()->id . ':' . $device['mac'] . ':income') ?? 0,
                'success'         => Redis::get(Auth()->user()->id . ':' . $device['mac'] . ':success') ?? 0,
                'fail'            => Redis::get(Auth()->user()->id . ':' . $device['mac'] . ':fail') ?? 0,
                'status'          => array_reverse($status),
                'add_amount_card' => $add_amount_card,
            ];
        });

        $send = $request->input('send') == 'SENDING' ? true : false;
        $can_send = $this->thisTimeCanSend();
        $next_can_send = $can_send['can_send'];
        $next_can_send_time = $can_send['can_send_time'];
        if ($send && $can_send['can_send']) {  //如果请求发送短信
            $next_can_send = false;
            $next_can_send_time = Carbon::now()->addSeconds(config('frequency'))->toDateTimeString();
            Redis::set(Auth()->user()->id . ':can-send-time', $next_can_send_time);  //设置下次可以发短信的时间
            $add_amount_card = $real_device->pluck('add_amount_card')->map(function ($item) {
                return collect($item)->where('has_card', true);
            })->flatten(1);
            return $this->responseSuccess($this->sendMessage($add_amount_card));
//            Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':success', 1);  //成功
//            Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':income', 1);
//            Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':fail', 1);
//            此处给该卡加钱！！！！！！！！！！！！！！
        }

        $can_send['real_device'] = $real_device;

        return $this->responseSuccess([
            'can_send'      => $next_can_send,
            'can_send_time' => $next_can_send_time,
            'frequency'     => $can_send['frequency'],
            'real_device'   => $real_device,
        ]);
    }

    public function thisTimeCanSend()
    {
        $this_time = Carbon::now()->toDateTimeString();
        $can_send_time = Redis::get(Auth()->user()->id . ':can-send-time') ?? $this_time;
        $can_send = true;
        if ($can_send_time > $this_time) {
            $can_send = false;
        }

        return [
            'can_send'      => $can_send,
            'can_send_time' => $can_send_time,
            'frequency'     => config('frequency') * 1000,
        ];
    }

    /**
     * 发送短信
     */
    public function sendMessage($add_amount_card)
    {
        $wait_count = $add_amount_card->count();
        $task_list = [];
        if($wait_count > 0) {
            do {
                $task = $this->getRandomTask(); //随机获取一条任务
                if(!$task){
                    break;
                }
                if ($task->unfinished >= $wait_count) {
                    if($task->unfinished == $wait_count){
                        Task::where('id', $task['id'])->update([
                            'status'            => 'COMPLETED',
                            'unfinished'        => 0,
                            'running'           => false
                        ]);
                    }else{
                        Task::where('id', $task['id'])->update([
                            'unfinished' => $task['unfinished'] - $wait_count
                        ]);
                    }
                    $task_list[] = [
                        'id'      => $task['id'],
                        'content' => $task['content'],
                        'mobile'  => $this->getTaskMobile($task['mobile'], $task['count'], $task['unfinished'], $wait_count),
                    ];
                    $wait_count = 0;
                }else{
                    Task::where('id', $task['id'])->update([
                        'status'            => 'COMPLETED',
                        'unfinished'        => 0,
                        'running'           => false
                    ]);
                    $task_list[] = [
                        'id'      => $task['id'],
                        'content' => $task['content'],
                        'mobile'  => $this->getTaskMobile($task['mobile'], $task['count'], $task['unfinished'], $wait_count),
                    ];

                    $wait_count = $wait_count - $task['unfinished'];
                }

            } while ($wait_count != 0);
        }

        if(count($task_list) == 0){
            return false;   //没有任务
        }

        dd($task_list);


//        Redis::pipeline(function ($pipe) use ($task_list) {
//            需要写文件了;
//        });


    }

    /**
     * 返回需要处理的电话
     * @param $mobile
     * @param $count
     * @param $unfinished
     * @param $wait_count
     * @return bool|string
     */
    public function getTaskMobile($mobile, $count, $unfinished, $wait_count)
    {
        if($unfinished == 0 || $wait_count > $unfinished || $unfinished > $count){
            return false;
        }

        $finished = ($count - $unfinished) * 12;   //已完成的

        if($unfinished == $wait_count){
            return substr($mobile, $finished);
        }

        $end_finished = $wait_count * 12 -1;

        return substr($mobile, $finished, $end_finished);
    }

    /**
     * 随机获取一条进行中的任务
     * @return mixed
     */
    public function getRandomTask()
    {
        return Task::where([
            'status'  => 'UNDONE',
            'running' => true
        ])->select('id', 'content', 'count', 'unfinished', 'mobile')->inRandomOrder()->first();
    }
}
