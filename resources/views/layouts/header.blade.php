<div id="homeBar" class="header" style="background-color: #999999;padding: 10px">
    <a href="{{ route('home') }}" class="btn btn-default top-btn">设备管理</a>
    <a href="{{ route('detail') }}" class="btn btn-default top-btn">明细管理</a>
    <a href="{{ route('info_edit') }}" class="btn btn-default top-btn">账户信息</a>
    <a href="{{ route('config') }}" class="btn btn-default top-btn">系统设置</a>
    <a href="{{ route('help') }}" class="btn btn-default top-btn">帮助中心</a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default top-btn" style="float: right; background-color: #999999; color: white">注销</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</div>
@if(config('announcement') != 'null')
    <div style="background-color:#999999;width: 100%;padding: 10px;color: white">{!!  config('announcement') !!}</div>
@endif()
