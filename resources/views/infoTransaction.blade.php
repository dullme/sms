@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex; height: 100%">
        @include('layouts.left')
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <table class="table table-bordered" style="background-color: white; margin-top: 8px">
                <thead>
                <tr>
                    <th>申请时间</th>
                    <th>状态</th>
                    <th>对应账户</th>
                    <th>银行名称</th>
                    <th>交易金额/剩余金额</th>
                    <th>确认时间</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach($withdraw as $item)
                    <tr>
                        <th scope="row">{{ $item->created_at }}</th>
                        <td style="color: {{ \App\Withdraw::$colors[$item->status] }}">{{ \App\Withdraw::$status[$item->status]}}</td>
                        <td>{{ $item->bank_card_number }}</td>
                        <td>{{ $item->bank }}</td>
                        @if($item->status == 8 || $item->status == 9)
                            <td>{{ $item->amount }}/{{ $item->balance + $item->amount }}</td>
                        @else
                            <td>{{ $item->amount }}/{{ round($item->balance - $item->amount, 4) }}</td>
                        @endif
                        <td>{{ $item->payment_at }}</td>
                        <td>{{ $item->remark }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $withdraw->links() }}
            </div>
        </div>
    </div>
@endsection
