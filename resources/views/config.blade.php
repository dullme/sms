@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="padding: 10px">
        <div class="title_right">
            <strong>系统设置</strong>
        </div>
        <div style="display: flex;height: 100%">
            <div style="float: left;padding: 0px; min-width: 600px; width: 100%">
                <form method="POST" action="{{ route('config') }}" style="margin-left: 20px;font-size: 12px; font-weight: bolder;">
                    @csrf
                    <div style="margin-top: 10px;">
                        <label style="width: 200px">运行状态 ： &nbsp;</label>
                        <span class="text-success">正常运行中...</span>
                    </div>

                    <div style="margin-top: 10px">
                        <label style="width: 200px">波特率 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="baud_rate">
                            <option {{ Auth()->user()->baud_rate == 1200 ? 'selected="selected"' :'' }}>1200</option>
                            <option {{ Auth()->user()->baud_rate == 2400 ? 'selected="selected"' :'' }}>2400</option>
                        </select>
                    </div>

                    <div style="margin-top: 10px;">
                        <label style="width: 200px">SIM卡类别 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="country">
                            <option value="">请选择</option>
                            @foreach( \App\Country::all() as $item)
                                <option value="{{ $item->country_iccid }}" {{ Auth()->user()->country == $item->country_iccid ? 'selected="selected"':'' }}>{{ $item->country_name }}</option>
                            @endforeach
                        </select>
                        @if(Session::has('country_info'))
                            <span class="text-danger" style="margin-left: 10px">{{ Session::get('country_info') }}</span>
                        @endif

                    </div>

                    <div style="margin-top: 10px">
                        <label style="width: 200px">单卡单日发送上限 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="one_day_max_send_count">
                            <option value="0">请选择</option>
                            @foreach(oneDayMaxSendCount() as $item)
                                <option value="{{ $item }}" {{ Auth()->user()->one_day_max_send_count == $item ? 'selected="selected"' :'' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-top: 10px">
                        <label style="width: 200px">是否开启防封模式 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="mode">
                            <option value="0" {{ Auth()->user()->mode == 0 ? 'selected="selected"' :'' }}>关闭</option>
                            <option value="1" {{ Auth()->user()->mode == 1 ? 'selected="selected"' :'' }}>开启</option>
                        </select>
                    </div>

                    <div style="margin-top: 10px">
                        <label style="width: 200px">通道加密选择 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="encryption">
                            <option value="0" {{ Auth()->user()->encryption == 0 ? 'selected="selected"' :'' }}>不加密</option>
                            <option value="1" {{ Auth()->user()->encryption == 1 ? 'selected="selected"' :'' }}>加密</option>
                        </select>
                    </div>

                    <div style="margin-top: 10px">
                        <label style="width: 200px">设备类型 ： &nbsp;</label>
                        <select style="width: 180px; height: 26px" name="equipment">
                            <option value="64" {{ Auth()->user()->equipment == 64 ? 'selected="selected"' :'' }}>64</option>
                            <option value="128" {{ Auth()->user()->equipment == 128 ? 'selected="selected"' :'' }}>128</option>
                            <option value="256" {{ Auth()->user()->equipment == 256 ? 'selected="selected"' :'' }}>256</option>
                        </select>
                    </div>

                    <div style="margin-left: 204px;margin-top: 20px;float: left">
                        <input type="submit" value="保    存" style="font-size: 12px;cursor: pointer;padding: 4px 26px">
                        <span style="font-size: 12px;color: #ce0000;">
                            @if(Session::has('saveConfig'))
                                {{ Session::get('saveConfig') }}
                            @elseif($errors->has('country'))
                                {{ $errors->first('country') }}
                            @endif
                        </span>
                    </div>
                </form>

                <div style="margin-top: 100px; font-size: 12px; font-weight: bold; margin-left: 20px">
                    <span>少部分网络运营商开启防封模式和通道加密后，会使用不正常，请谨慎修改</span>
                </div>
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
