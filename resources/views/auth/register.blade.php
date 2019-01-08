@extends('layouts.app')

@section('content')
    <div class="container" style="width: 600px; min-width: 600px">
        <div style="">
            <div style="font-size: 40px; font-weight: bolder;background-color: #D7D7D7">欢迎使用SMS融云平台</div>
            <form method="POST" action="{{ route('register') }}" style="font-size: 18px; font-weight: bolder;">
                @csrf
                <div style="margin-top: 100px">
                    <label>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号 ： &nbsp;</label>
                    <input type="text" style="width: 200px" name="username" value="{{ old('username') }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 6-20位数字+英文字母</label>
                </div>

                <div style="margin-top: 18px">
                    <label>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码 ： &nbsp;</label>
                    <input type="password" style="width: 200px;" name="password" value="{{ old('password') }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 6-20位数字+英文字母</label>
                </div>
                <div style="margin-top: 18px">
                    <label>确认密码 ： &nbsp;</label>
                    <input type="password" style="width: 200px;" name="password_confirmation">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 确认密码</label>
                </div>
                <div style="margin-top: 18px">
                    <label>密保问题 ： &nbsp;</label>
                    <input type="text" style="width: 200px;" name="security_question" value="{{ old('security_question') }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 请输入一个自定义问题，并牢记</label>
                </div>
                <div style="margin-top: 18px">
                    <label>密保答案 ： &nbsp;</label>
                    <input type="password" style="width: 200px;" name="classified_answer" value="{{ old('classified_answer') }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 请输入一个自定义答案，并牢记</label>
                </div>
                <div style="margin-top: 18px">
                    <label>推&nbsp;&nbsp;荐&nbsp;&nbsp;码 ： &nbsp;</label>
                    <input type="password" style="width: 200px;" name="code" value="{{ old('code') }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 请输入推荐码</label>
                </div>
                <div class="text-danger" style="height: 24px; font-size: 16px;margin-left: 110px">
                    @if ($errors->has('username'))
                        {{ $errors->first('username') }}
                    @elseif($errors->has('password'))
                        {{ $errors->first('password') }}
                    @elseif($errors->has('password_confirmation'))
                        {{ $errors->first('password_confirmation') }}
                    @elseif($errors->has('security_question'))
                        {{ $errors->first('security_question') }}
                    @elseif($errors->has('classified_answer'))
                        {{ $errors->first('classified_answer') }}
                    @elseif($errors->has('code'))
                        {{ $errors->first('code') }}
                    @endif
                </div>

                <div style="margin-top: 30px; margin-left: 110px">
                    <input type="submit" class="btn btn-lg btn-default" value="注    册" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">
                </div>

                <div style="margin-top: 46px; font-size: 16px; margin-left: 80px">
                    <a href="{{ route('register') }}" style="color: #1C9BCC; text-decoration:none">账号注册</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('reset_password') }}" style="color: #1C9BCC; text-decoration:none">找回密码</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('login') }}" style="color: #1C9BCC; text-decoration:none">用户登陆</a>
                </div>

            </form>
        </div>
    </div>
@endsection
