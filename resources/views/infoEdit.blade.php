@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex; height: 100%">
        @include('layouts.left')
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <form method="POST" action="{{ route('info_edit') }}" style="margin-left: 150px;font-size: 18px; font-weight: bolder;">
                @csrf
                <div style="margin-top: 100px">
                    <label>真实姓名 ： &nbsp;</label>
                    <input type="text" style="width: 250px" name="real_name" value="{{ Auth()->user()->real_name }}">
                </div>

                <div style="margin-top: 18px;">
                    <label>提款银行 ： &nbsp;</label>
                    <select style="width: 250px; height: 34px" name="bank">
                        <option>请选择</option>
                        @foreach( getBank() as $item)
                            <option value="{{ $item }}" {{ Auth()->user()->bank == $item ? 'selected="selected"':'' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-top: 18px">
                    <label>提款账户 ： &nbsp;</label>
                    <input type="text" style="width: 250px;" name="bank_card_number" value="{{ Auth()->user()->bank_card_number }}">
                </div>
                <div style="margin-top: 18px">
                    <label>资金密码 ： &nbsp;</label>
                    <input type="password" style="width: 250px;" name="withdraw_password" value="{{ Auth()->user()->withdraw_password }}">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 资金密码修改后24小时内不可提现</label>
                </div>
                <div style="margin-top: 18px">
                    <label>登陆密码 ： &nbsp;</label>
                    <input type="password" style="width: 250px;" name="password" value="{{ old('password') }}">
                    <label class="text-danger" style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 凭登陆密码修改以上信息</label>
                </div>
                <div class="text-danger" style="height: 24px; font-size: 16px;margin-left: 110px">
                    @if(Session::has('editInfo'))
                        {{ Session::get('editInfo') }}
                    @elseif ($errors->has('real_name'))
                        {{ $errors->first('real_name') }}
                    @elseif($errors->has('bank'))
                        {{ $errors->first('bank') }}
                    @elseif($errors->has('bank_card_number'))
                        {{ $errors->first('bank_card_number') }}
                    @elseif($errors->has('withdraw_password'))
                        {{ $errors->first('withdraw_password') }}
                    @elseif($errors->has('password'))
                        {{ $errors->first('password') }}
                    @endif
                </div>

                <div style="margin-top: 30px; margin-left: 110px">
                    <input type="submit" class="btn btn-lg btn-default" value="修    改" style="width: 160px;background-color: white; font-weight: bolder; border: 2px solid #BBBBBB">
                </div>
            </form>
        </div>
    </div>
@endsection
