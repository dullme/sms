<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height: 100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body style="min-width: 900px; height: 100%">
    <div id="app" style="height: 100%">
        <main class="py-0" style="height: 100%">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
    <script type="text/javascript">
        // 顶部菜单高亮
        var currentMenu = $('#homeBar a[href="'+window.location.origin + window.location.pathname+'"]:first');
        if(window.location.pathname == '/info/withdraw' || window.location.pathname == '/info/transfer' || window.location.pathname == '/info/transaction'){
            currentMenu = $('#homeBar a[href="'+window.location.origin + '/info/edit'+'"]:first');
            currentMenu.addClass('active');
        }else if(currentMenu){
            currentMenu.addClass('active');
        }
        // 顶部菜单高亮
        var menu = $('#menu a[href="'+window.location.origin + window.location.pathname+'"]:first');
        if (menu) menu.addClass('active');
    </script>
</body>
</html>
