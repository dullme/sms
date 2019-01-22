<?php

namespace App\Http\Controllers;

use App\Card;
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
        if(mb_strlen($user->real_name) ==2){
            $user->real_name = '*'.mb_substr($user->real_name, -1);
        }elseif (mb_strlen($user->real_name) > 2){
            $ss = '';
            for ($i = 1; $i <= mb_strlen($user->real_name)-2; $i++){
                $ss.='*';
            }
            $user->real_name = mb_substr($user->real_name, 0,1).$ss.mb_substr($user->real_name,-1);
        }

        if ($user) {
            return $this->responseSuccess($user);
        } else {
            return $this->setStatusCode(422)->responseError('找不到该用户');
        }
    }

    public function getMyDevice()
    {
        $user = Redis::set('user:device:' . Auth()->user()->id, '123');

        dd(Redis::get('user:device:' . Auth()->user()->id));
    }

    public function setMyDevice(Request $request)
    {
        $ip = $request->get('ip');
        $mac = $request->get('mac');

        Redis::set(Auth()->user()->id .':'.$mac. ':ip', $ip);

        return $this->responseSuccess([
            'ip'        => $ip,
            'mac'       => $mac,
            'income'    => Redis::get(Auth()->user()->id . ':' . $ip . ':income') ?? 0,
            'success'   => Redis::get(Auth()->user()->id . ':' . $ip . ':success') ?? 0,
            'fail'      => Redis::get(Auth()->user()->id . ':' . $ip . ':fail') ?? 0,
            'frequency' => config('frequency', '1') * 1000,
        ]);
    }

    /**
     * 发送短信
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $real_device = $request->input('real_device');
        $device = collect($real_device)->map(function ($item){
            return [
                'ip' => $item['ip'],
                'mac' => $item['mac'],
                'status' => collect($item['status'])->flatten(2)->where('has_card', true)->where('status', 'success'),
            ];
        });

        $res = collect($device)->map(function ($item){
            $cards = Card::whereIn('name', $item['status']->pluck('iccid'))->select('id', 'name', 'password', 'status')->get();
            $item['status'] = $item['status']->map(function ($status) use ($cards){
                $card = $cards->where('name', $status['iccid'])->first();
                if($card){
                    if($card->password != $status['imsi'] || $card->status == 1 ){
                        $status['status'] = 'wrong';
                    }else{
//                        Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':success', 1);  //成功
//                        Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':income', 1);
//                        Redis::incrby(Auth()->user()->id . ':' . $item['ip'] . ':fail', 1);
                        //此处给该卡加钱！！！！！！！！！！！！！！
                    }
                }else{
                    $status['status'] = 'unknown';
                }

                return $status;
            });

            return $item;
        });

//dd($res);

        foreach ($real_device as $d_index=>$device){
            foreach ($device['status'] as $s_index=>$status){
                foreach ($status as $r_index=>$row){
                    foreach ($row as $v_index=>$value){
                        if($value['has_card'] == true && $value['status'] == 'success'){
                            $real_device[$d_index]['status'][$s_index][$r_index][$v_index] = $res->where('mac', $device['mac'])->first()['status']->where('iccid', $value['iccid'])->first();
                        }
                    }
                }
            }
        }

        return $this->responseSuccess($real_device);
    }
}
