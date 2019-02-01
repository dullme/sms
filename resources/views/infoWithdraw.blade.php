@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex;height: 100%">
        @include('layouts.left')
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <div style="margin-top:40px; margin-left: 100px;font-size: 16px; font-weight: bolder;">
                <span>您当前账户余额为：{{ Auth()->user()->amount }}元，可提现金额为：{{ intval(Auth()->user()->amount / 100) * 100 }}元</span>
            </div>
            <form method="POST" action="{{ route('info_withdraw') }}" style="margin-top: 20px;margin-left: 150px;font-size: 18px; font-weight: bolder;">
                @csrf
                <div style="margin-top: 30px">
                    <label>提现金额 ： &nbsp;</label>
                    <input type="text" style="width: 250px" name="withdraw_amount" value="{{ old('withdraw_amount') }}">
                </div>

                <div style="margin-top: 18px">
                    <label>资金密码 ： &nbsp;</label>
                    <input type="password" style="width: 250px;" name="withdraw_password">
                </div>

                <div class="text-danger" style="height: 24px; font-size: 16px;margin-left: 110px">
                    @if(Session::has('withdrawInfo'))
                        {{ Session::get('withdrawInfo') }}
                    @elseif ($errors->has('withdraw_amount'))
                        {{ $errors->first('withdraw_amount') }}
                    @elseif($errors->has('withdraw_password'))
                        {{ $errors->first('withdraw_password') }}
                    @endif
                </div>

                <div style="margin-top: 30px; margin-left: 110px">
                    <input type="submit" class="btn btn-lg btn-default" value="提    现" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">
                </div>
            </form>
            <div style="margin-top: 80px; font-size: 16px; font-weight: bold; margin-left: 100px">
                <p>注意：</p>
                <div style="margin-left: 40px">
                    @foreach(explode('<br/>', config('withdraw_info')) as $info)
                        <p>{{ $info }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
