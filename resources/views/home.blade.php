@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <div style="padding: 10px; min-width: 600px">
        <table class="table table-bordered" style="background-color: white;">
            <thead>
                <tr>
                    <th>当前设备</th>
                    <th>状态</th>
                    <th>当日收益</th>
                    <th>当日成功条数</th>
                    <th>当日失败条数</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">192.168.1.67</th>
                    <td class="text-success">通讯正常</td>
                    <td>50</td>
                    <td>12</td>
                    <td>5</td>
                </tr>
                <tr>
                    <th scope="row">192.168.1.32</th>
                    <td>通讯正常</td>
                    <td>43</td>
                    <td>40</td>
                    <td>3</td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <a  class="btn btn-lg btn-default" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">搜索新设备</a>
        </div>
    </div>

@endsection
