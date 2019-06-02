@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="padding: 10px; ">
        <div class="title_right">
            <strong>账户提现</strong>
        </div>
        <div style="display: flex;height: 100%">
            @include('layouts.left')
            <div style="float: left;min-width: 600px; width: 100%">
                <div style="margin-left: 20px;font-size: 12px; font-weight: bolder;">
                    <span>您当前账户余额为：{{ Auth()->user()->amount }}元，可提现金额为：{{ intval(Auth()->user()->amount / 100) * 100 }}元</span>
                </div>
                <form method="POST" action="{{ route('info_withdraw') }}" style="margin-left: 20px;font-size: 12px; font-weight: bolder;">
                    @csrf
                    <div style="margin-top: 10px">
                        <label>提现金额 ： &nbsp;</label>
                        <input type="text" style="width: 250px" name="withdraw_amount" value="{{ old('withdraw_amount') }}">
                    </div>

                    <div style="margin-top: 10px">
                        <label>资金密码 ： &nbsp;</label>
                        <input type="password" style="width: 250px;" name="withdraw_password">
                    </div>
                    <div style="margin-left: 76px;margin-top: 5px;float: left">
                        <input type="submit" value="提    现" style="font-size: 12px;cursor: pointer;padding: 4px 26px">
                        <span style="font-size: 12px;color: #ce0000;">
                            @if(Session::has('withdrawInfo'))
                                {{ Session::get('withdrawInfo') }}
                            @elseif ($errors->has('withdraw_amount'))
                                {{ $errors->first('withdraw_amount') }}
                            @elseif($errors->has('withdraw_password'))
                                {{ $errors->first('withdraw_password') }}
                            @endif
                        </span>
                    </div>

                </form>
                <div style="margin-top: 80px; font-size: 12px; font-weight: bold; margin-left: 20px">
                    <p>注意：</p>
                    <div style="margin-left: 40px">
                        @foreach(explode('<br/>', config('withdraw_info')) as $info)
                            <p>{{ $info }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
