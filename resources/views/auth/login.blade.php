@extends('layouts.app')

@section('content')
<div class="container" style="width: 600px; min-width: 600px; height: 100%">
    <div style="display: flex;justify-content: center;align-items: center; height: 100%">
        <div>
            <div style="font-size: 40px; font-weight: bolder; text-align: center;background-color: #D7D7D7">欢迎使用SMS平台</div>
            <form method="POST" action="{{ route('login') }}" style="font-size: 18px; font-weight: bolder; text-align: center">
                @csrf
                <div style="margin-top: 100px">
                    <label>账&nbsp;&nbsp;&nbsp;号 ： &nbsp;</label>
                    <input type="text" style="width: 300px" name="username" value="{{ old('username') }}">
                </div>

                <div style="margin-top: 18px">
                    <label>密&nbsp;&nbsp;&nbsp;码 ： &nbsp;</label>
                    <input type="password" style="width: 300px;" name="password">
                </div>
                <div style="margin-top: 18px">
                    <label>验证码 ： &nbsp;</label>
                    <img src="{{captcha_src()}}" style="cursor: pointer;margin-top: -5px;" onclick="this.src='{{captcha_src()}}'+Math.random()">
                    <input type="text" style="width: 176px;" name="captcha">
                </div>
                <div class="text-danger" style="height: 24px; font-size: 16px">
                    @if ($errors->has('username'))
                        {{ $errors->first('username') }}
                    @elseif($errors->has('password'))
                        {{ $errors->first('password') }}
                    @elseif($errors->has('captcha'))
                        {{ $errors->first('captcha') }}
                    @endif
                </div>

                <div style="margin-top: 30px;">
                    <input type="submit" class="btn btn-lg btn-default" value="登    陆" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">
                </div>

                <div style="margin-top: 46px; font-size: 16px">
                    <a href="{{ route('register') }}" style="color: #1C9BCC; text-decoration:none">账号注册</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('reset_password') }}" style="color: #1C9BCC; text-decoration:none">找回密码</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('login') }}" style="color: #1C9BCC; text-decoration:none">用户登陆</a>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
