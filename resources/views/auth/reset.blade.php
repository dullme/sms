@extends('layouts.app-login')

@section('content')
    <div class="container" style="width: 600px; min-width: 600px; height: 100%">
        <div style="padding:100px 80px;width: 100%;">
            <div style="box-shadow: 0 2px 3px rgba(10, 10, 10, 0.1), 0 0 0 1px rgba(10, 10, 10, 0.1);">
                <div style="background-image: linear-gradient(to right , #2374E2,#023192 , #002D8E);border-radius: 8px 8px 0 0;text-align: center;">
                    <div style="color: #FED84D;font-size: 25px;padding: 30px 0;">欢迎使用SMS平台</div>
                </div>
                <div style="background-color: #EBE7F0; height: 5px"></div>
                <div style="height: 250px;background-color: #D7D7D7;border-radius: 0 0 8px 8px">
                    <form method="POST" action="{{ route('reset_password') }}" style="text-align: center;padding: 15px">
                        @csrf
                        <div>
                            <label>帐&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号&nbsp;： &nbsp;</label>
                            <input type="text" style="width: 200px" name="username" value="{{ old('username') }}">
                        </div>
                        <div style="margin-top: 5px">
                            <label>新&nbsp;密&nbsp;&nbsp;码&nbsp;： &nbsp;</label>
                            <input type="password" style="width: 200px;" name="password">
                        </div>
                        <div style="margin-top: 5px">
                            <label>确认密码 ： &nbsp;</label>
                            <input type="password" style="width: 200px;" name="password_confirmation">
                        </div>
                        <div style="margin-top: 5px">
                            <label>密保问题 ： &nbsp;</label>
                            <input type="text" style="width: 200px;" name="security_question" value="{{ old('security_question') }}">
                        </div>
                        <div style="margin-top: 5px">
                            <label>密保答案 ： &nbsp;</label>
                            <input type="text" style="width: 200px;" name="classified_answer" value="{{ old('classified_answer') }}">
                        </div>

                        <div style="margin-top: 5px;float: left">
                            <input type="submit" value="重    置" style="margin-left: 135px;font-size: 12px;cursor: pointer;padding: 4px 26px">
                            <span style="font-size: 12px;color: #ce0000;">
                                @if ($errors->has('username'))
                                    {{ $errors->first('username') }}
                                @elseif($errors->has('password'))
                                    {{ $errors->first('password') }}
                                @elseif($errors->has('security_question'))
                                    {{ $errors->first('security_question') }}
                                @elseif($errors->has('classified_answer'))
                                    {{ $errors->first('classified_answer') }}
                                @elseif(session('register'))
                                    {{ session('register') }}
                                @endif
                        </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="a-linked" style="clear: both;margin-top: 10px;text-align: center;color: rgba(255, 255, 255, 0.7); font-size: 12px">
                <a href="{{ route('register') }}" style="color: rgba(255, 255, 255, 0.7); text-decoration:none;">账号注册</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{ route('reset_password') }}" style="color: rgba(255, 255, 255, 0.7); text-decoration:none">找回密码</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{ route('login') }}" style="color: rgba(255, 255, 255, 0.7); text-decoration:none;">用户登陆</a>
            </div>
        </div>
    </div>
@endsection
