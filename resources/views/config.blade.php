@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex;height: 100%">
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <form method="POST" action="{{ route('config') }}" style="margin-left: 150px;font-size: 18px; font-weight: bolder;">
                @csrf
                <div style="margin-top: 100px;">
                    <label style="width: 200px">运行状态 ： &nbsp;</label>
                    <span class="text-success">正常运行中...</span>
                </div>

                <div style="margin-top: 18px">
                    <label style="width: 200px">波特率 ： &nbsp;</label>
                    <select style="width: 180px" name="baud_rate">
                        <option>1200</option>
                        <option>2400</option>
                    </select>
                </div>

                <div style="margin-top: 18px">
                    <label style="width: 200px">单卡单日发送上限 ： &nbsp;</label>
                    <select style="width: 180px" name="one_day_max_send_count">
                        <option value="0">请选择</option>
                        @foreach(oneDayMaxSendCount() as $item)
                            <option value="{{ $item }}" {{ Auth()->user()->one_day_max_send_count == $item ? 'selected="selected"' :'' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-top: 18px">
                    <label style="width: 200px">是否开启防封模式 ： &nbsp;</label>
                    <select style="width: 180px" name="mode">
                        <option value="0" {{ Auth()->user()->mode == 0 ? 'selected="selected"' :'' }}>关闭</option>
                        <option value="1" {{ Auth()->user()->mode == 1 ? 'selected="selected"' :'' }}>开启</option>
                    </select>
                </div>

                <div style="margin-top: 18px">
                    <label style="width: 200px">通道加密选择 ： &nbsp;</label>
                    <select style="width: 180px" name="encryption">
                        <option value="0" {{ Auth()->user()->encryption == 0 ? 'selected="selected"' :'' }}>不加密</option>
                        <option value="1" {{ Auth()->user()->encryption == 1 ? 'selected="selected"' :'' }}>加密</option>
                    </select>
                </div>

                <div class="text-danger" style="height: 24px; font-size: 16px;margin-left: 110px">
                    @if(Session::has('saveConfig'))
                        {{ Session::get('saveConfig') }}
                    @endif
                </div>

                <div style="margin-top: 30px; margin-left: 110px">
                    <input type="submit" class="btn btn-lg btn-default" value="保    存" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">
                </div>
            </form>

            <div style="margin-top: 100px; font-size: 16px; font-weight: bold; margin-left: 60px">
                <span>少部分网络运营商开启防封模式和通道加密后，会使用不正常，请谨慎修改</span>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $("#search").on( "click", function( event ) {
            if($("#select-status").val() == ''){
                window.location.href = "/detail";
            }else{
                window.location.href = "/detail?status="+ $("#select-status").val();
            }
        });
    </script>
@endsection
