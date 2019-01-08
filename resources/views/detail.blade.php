@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <div style="padding: 10px; min-width: 600px">
        <span style="font-size: 16px">您当日总接受任务条数为是:N条，成功:N条，失败: N条</span>
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

@endsection
