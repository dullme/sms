<?php

namespace App\Http\Controllers;

use App\Card;
use App\Task;
use App\TaskHistory;
use App\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Log;
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
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->orderBy('created_at', 'DESC')
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

        $one_day_max_send_count = config('one_day_max_send_count');

        $real_device = collect($device)->map(function ($device) use($one_day_max_send_count) {
            $unknown_count = 0;
            $base_status = collect($device['status']);
            $cards = Card::whereIn('name', $base_status->pluck('iccid'))->select('id', 'name', 'password', 'status', 'amount')->get();
            $status = [];
            $add_amount_card = [];
            $databases_card = [];
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
                                if ($databases_card->password != $card['imsi']) {
                                    $type = 'wrong';    //匹配失败的卡
                                }elseif ($databases_card->status == 1){
                                    $type = 'seal';    //匹配失败的卡
                                }else {
                                    if($databases_card->amount > 0){
                                        $date_string = ':' . date('Y-m-d', time());
                                        $daily_send_amount = Redis::get($databases_card->id . $date_string . ':card-send-count');

                                        if($databases_card->amount > config('more_amount')){
                                            $type = 'too_much_money';    //余额大于N元的，系统不给该卡排任务
                                        }elseif ($daily_send_amount >= $one_day_max_send_count){
                                            $type = 'daily_send_amount'; //单卡单日最高上限发送次数
                                        }else{
                                            $type = 'success';  //匹配成功的卡
                                        }
                                    }else{
                                        $type = 'insufficient_balance';  //余额不足
                                    }

                                }
                            } else {
                                $type = 'unknown';  //未知卡
                                ++$unknown_count;
                            }
                        }
                        $res = [
                            'port'     => $card['port'],
                            'iccid'    => $card['iccid'] ?? '',
                            'imsi'     => $card['imsi'] ?? '',
                            'st'       => $card['st'],
                            'has_card' => $card['st'] == 0 ? false : true,  //st为0时卡为空
                            'status'   => $type,
                            'card_id'   => optional($databases_card)->id,
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
                'status'          => array_reverse($status),
                'add_amount_card' => $add_amount_card,
                'unknown_count' => $unknown_count,
            ];
        });

        $send = $request->input('send') == 'SENDING' ? true : false;
        $can_send = $this->thisTimeCanSend();
        $next_can_send = $can_send['can_send'];
        $next_can_send_time = $can_send['can_send_time'];
        $add_amount_card = [];
        if ($send && $can_send['can_send'] && $real_device->sum('unknown_count') <= 5) {  //如果请求发送短信
            $next_can_send = false;
            $next_can_send_time = Carbon::now()->addSeconds(config('frequency'))->toDateTimeString();
            Redis::set(Auth()->user()->id . ':can-send-time', $next_can_send_time);  //设置下次可以发短信的时间
            $add_amount_card = $real_device->pluck('add_amount_card')->map(function ($item) {
                return collect($item)->where('has_card', true);
            })->flatten(1);
            $add_amount_card = $this->sendMessage($add_amount_card);   //这里要处理是否成功
        }

        $date_string = ':' . date('Y-m-d', time());

        return $this->responseSuccess([
            'income'        => Redis::get(Auth()->user()->id . $date_string . ':income') ?? 0,
            'success'       => Redis::get(Auth()->user()->id . $date_string . ':success') ?? 0,
            'fail'          => Redis::get(Auth()->user()->id . $date_string . ':fail') ?? 0,
            'can_send'      => $next_can_send,
            'can_send_time' => $next_can_send_time,
            'frequency'     => $can_send['frequency'],
            'real_device'   => $real_device,
            'add_amount_card'   => $add_amount_card,
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
        if ($wait_count > 0) {
            do {
                $task = $this->getRandomTask(); //随机获取一条任务
                if (!$task) {
                    break;
                }
                if ($task->unfinished >= $wait_count) {
                    if ($task->unfinished == $wait_count) {
                        Task::where('id', $task['id'])->update([
                            'status'     => 'COMPLETED',
                            'unfinished' => 0,
                            'running'    => false
                        ]);
                    } else {
                        Task::where('id', $task['id'])->update([
                            'unfinished' => $task['unfinished'] - $wait_count
                        ]);
                    }
                    $task_list[] = [
                        'id'      => $task['id'],
                        'content' => $task['content'],
                        'amount'  => $task['amount'],
                        'mobile'  => $this->getTaskMobile($task['mobile'], $task['count'], $task['unfinished'], $wait_count),
                    ];
                    $wait_count = 0;
                } else {
                    Task::where('id', $task['id'])->update([
                        'status'     => 'COMPLETED',
                        'unfinished' => 0,
                        'running'    => false
                    ]);
                    $task_list[] = [
                        'id'      => $task['id'],
                        'content' => $task['content'],
                        'amount'  => $task['amount'],
                        'mobile'  => $this->getTaskMobile($task['mobile'], $task['count'], $task['unfinished'], $wait_count),
                    ];

                    $wait_count = $wait_count - $task['unfinished'];
                }

            } while ($wait_count != 0);
        }
        if (count($task_list) == 0) {
            return [];   //没有任务
        }

        $task_list = collect($task_list)->map(function ($item) use ($add_amount_card) {
            $mobiles = collect(explode(',', $item['mobile']));
            $mobiles = $mobiles->map(function ($mobile) use ($add_amount_card) {
                return array_merge(['mobile' => $mobile], $add_amount_card->pop());
            });

            $success_count = $mobiles->where('status', 'success')->count();
            $fail_count = $mobiles->whereIn('status', ['unknown','wrong', 'insufficient_balance', 'daily_send_amount'])->count();
            $date_string = ':' . date('Y-m-d', time());
            Redis::incrby(Auth()->user()->id . $date_string . ':success', $success_count);  //成功
            Redis::incrby(Auth()->user()->id . $date_string . ':fail', $fail_count);   //失败
            $decrement_price = intval(config('cost_price') * 10000);    //需要扣除iccid的钱
            $income_price = intval($item['amount'] * 10000);    //用户增加的钱

            Redis::incrby(Auth()->user()->id . $date_string . ':income', $income_price * $success_count);
            $success = $mobiles->where('status', 'success');

            if(count($success->pluck('card_id')->unique())){
                $card_id = $success->pluck('card_id')->unique();
                $save_card = Card::whereIn('id', $card_id)->decrement('amount', $decrement_price);    //扣除卡上的钱
                if(!$save_card){
                    Log::channel('money_error')->info('给用户ID为:'.Auth()->user()->id.'扣除'.$decrement_price.'失败！'.json_encode($success->pluck('card_id')->unique()).($income_price * $success_count));
                }
            }

            if($income_price * $success_count > 0){
                $save_user = User::where('id', Auth()->user()->id)->increment('amount', $income_price * $success_count); //增加用户的钱
                if(!$save_user){
                    Log::channel('money_error')->info('给用户ID为:'.Auth()->user()->id.'增加'.($income_price * $success_count) .'失败！');
                }
            }


            return [
                'id'           => $item['id'],
                'content'      => $item['content'],
                'income_price' => $income_price,
                'mobile'       => $mobiles,
            ];
        });

        $task_list->map(function ($task){
            $task_histories = $task['mobile']->map(function ($mobile) use ($task){
                $created_at = Carbon::now()->toDateTimeString();
                $status = in_array($mobile['status'], ['success']) ? true : false;
                $date_string = $date_string = ':' . date('Y-m-d', time());
                Redis::incrby($mobile['card_id'] . $date_string . ':card-send-count', 1);  //单卡发送次数
                return [
                    'user_id' => Auth()->user()->id,
                    'task_id' => $task['id'],
                    'ip' => $mobile['ip'],
                    'iccid' => $mobile['iccid'],
                    'imsi' => $mobile['imsi'],
                    'status' => $status,
                    'mobile' => $mobile['mobile'],
                    'amount' => $task['income_price'],
                    'remark' => TaskHistory::$remark[$mobile['status']],
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                ];
            });
            $res = TaskHistory::insert($task_histories->toArray());
            if(!$res){
                Log::channel('task_history')->info('这些日志保存到数据库失败------>'.json_encode($task_histories->toArray()));
            }
        });

        return $task_list;
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

        $finished = ($count - $unfinished) * 12;   //已完成的

        if ($unfinished == $wait_count) {
            return substr($mobile, $finished);
        }

        $end_finished = $wait_count * 12 - 1;

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
            ])
            ->where('unfinished', '!=', 0)
            ->select('id', 'content', 'count', 'unfinished', 'mobile', 'amount')
            ->inRandomOrder()
            ->first();
    }
}
