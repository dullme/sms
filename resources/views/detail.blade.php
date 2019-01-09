@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex; height: 100%">
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <span style="font-size: 16px">您当日总接受任务条数为是: {{ $fail + $success }}条，成功: {{ $success }}条，失败: {{ $fail }}条</span>
            <select id="select-status" style="margin-left: 10px;min-width: 80px">
                <option value="all" {{ isset($_REQUEST['status'])? '' :'selected="selected"' }}>全部</option>
                <option value="fail" {{ isset($_REQUEST['status']) && $_REQUEST['status'] == 'fail'? 'selected="selected"' :'' }}>失败</option>
                <option value="success" {{ isset($_REQUEST['status']) && $_REQUEST['status'] == 'success'? 'selected="selected"' :'' }}>成功</option>
            </select>
            <a id="search" style="margin-left: 5px;background-color: white; padding: 1px 10px; color: black; border: 1px solid rgb(166,166,166); border-radius: 4px; text-decoration: none; cursor: pointer">筛选</a>
            <table class="table table-bordered" style="background-color: white; margin-top: 8px">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>发送状态</th>
                    <th>ICCID</th>
                    <th>目的号码</th>
                    <th>发送内容</th>
                    <th>收益</th>
                </tr>
                </thead>
                <tbody>
                @foreach($task_histories as $history)
                    <tr>
                        <th style="min-width: 180px;" scope="row">{{ $history->created_at }}</th>
                        <td style="min-width: 100px;" class="text-{{ $history->status ? 'success' :'danger' }}">{{ $history->status ? '成功' :'失败' }}</td>
                        <td>{{ $history->iccid }}</td>
                        <td>{{ $history->mobile }}</td>
                        <td style="max-width: 200px; min-width: 200px;">{{ $history->task->content }}</td>
                        <td>{{ $history->task->amount }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $task_histories->links() }}
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
