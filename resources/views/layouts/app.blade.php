<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>欢迎使用SMS平台</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/css.css') }}" />
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sdmenu.js') }}"></script>
    {{--<style>--}}
        {{--.Switch{--}}
            {{--background-color: #5D6782;--}}
        {{--}--}}
    {{--</style>--}}
</head>

<body class="win98" style="font-family: 'SimSun'">
{{--<div class="header">--}}
    {{--<div class="logo" style="    font-size: 16px;color: white;padding: 20px;">欢迎使用SMS平台</div>--}}

    {{----}}
{{--</div>--}}
<!-- 顶部 -->

<div id="middle">
    <div class="left">
        <div style="color: black;border-bottom:1px solid #ddd;padding: 20px 0;text-align: center;font-size: 18px">欢迎使用SMS平台</div>
        <script type="text/javascript">
            var myMenu;
            window.onload = function() {
                myMenu = new SDMenu("my_menu");
                myMenu.init();
            };
        </script>

        <div class="menu">
            <ul class="menu-content">
                <a href="{{ route('home') }}">
                    <li class="item">
                        <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                        设备中心
                    </li>
                </a>
                <a href="{{ route('detail') }}">
                    <li class="item">
                        <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                        明细管理
                    </li>
                </a>
                <li class="item folder">
                    <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                    账户信息
                    <ul class="menu-content">
                        <a href="{{ route('info_edit') }}">
                            <li class="item">
                                <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                                信息修改
                            </li>
                        </a>
                        <a href="{{ route('info_withdraw') }}">
                            <li class="item">
                                <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                                账户提现
                            </li>
                        </a>
                        <a href="{{ route('info_transaction') }}">
                            <li class="item">
                                <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                                交易查询
                            </li>
                        </a>
                    </ul>
                </li>
                <a href="{{ route('config') }}">
                    <li class="item">
                        <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                        系统设置
                    </li>
                </a>
                <a href="{{ route('help') }}">
                    <li class="item">
                        <img class="icon" src="{{ asset('icons/favourites.gif') }}">
                        帮助中心
                    </li>
                </a>
            </ul>
        </div>
        <div style="margin-left: 21px;">
            <a href="#myModal123" role="button" data-toggle="modal"><span class="glyphicon glyphicon-off"></span> 注销</a>
            <!-- Modal -->
            <div class="modal fade" id="myModal123" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top: 30%;">
                <div class="modal-dialog" role="document" style="width: 350px;">
                    <div class="modal-content">
                        <div class="resizable window">
                            <div class="header-win">
                                <img class="icon" src="icons/txt.gif">
                                注销系统
                                <div class="buttons">
                                    <button type="button" data-dismiss="modal">X</button>
                                </div>
                            </div>

                            <div class="content">
                                <center>
                                    <h5>您确定要注销退出系统吗?</h5>
                                    <p></p>
                                    <button type="button" data-dismiss="modal">关闭</button>
                                    <button type="button" onclick="event.preventDefault();document.getElementById('logout-form').submit();">确定</button>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </center>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Switch"></div>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $(".Switch").click(function(){
                $(".left").toggle();

            });
        });
    </script>

    <div class="right"  id="mainFrame">
        <div id="app">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>