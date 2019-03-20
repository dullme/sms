@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex; height: 100%">
        @include('layouts.left')
        <div style="float: left;padding: 10px; min-width: 600px; width: 100%">
            <form method="POST" action="{{ route('info_edit') }}" style="margin-left: 150px;font-size: 18px; font-weight: bolder;" enctype="multipart/form-data">
                @csrf
                <div style="margin-top: 100px">
                    <label>当前账号 ： &nbsp;</label>
                    <span>{{ Auth()->user()->username }}</span>
                </div>
                {{--<div style="margin-top: 18px">--}}
                    {{--<label>邀&nbsp;&nbsp;请&nbsp;&nbsp;码 ： &nbsp;</label>--}}
                    {{--<span>{{ Auth()->user()->code }}</span>--}}
                {{--</div>--}}
                <div style="margin-top: 18px">
                    <label>真实姓名 ： &nbsp;</label>
                    @if(Auth()->user()->real_name)
                        <span>{{ Auth()->user()->real_name }}</span>
                        @else
                        <input type="text" style="width: 250px" name="real_name" value="{{ Auth()->user()->real_name }}">
                    @endif

                </div>

                <div style="margin-top: 18px;">
                    <label>提款银行 ： &nbsp;</label>
                    <select style="width: 250px; height: 34px" name="bank" id="select_bank">
                        <option value="">请选择</option>
                        @foreach( \App\Bank::all() as $item)
                            <option data-type="{{ $item->type }}" value="{{ $item->name }}" {{ Auth()->user()->bank == $item->name ? 'selected="selected"':'' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-top: 18px;display: none" id="display-alipay">
                    <label>收&nbsp;&nbsp;款&nbsp;&nbsp;码 ： &nbsp;</label>
                    <input type="file" style="width: 250px;" name="alipay">
                    <input id="alipay_hidden" type="hidden" name="alipay_hidden" value="{{ Auth()->user()->alipay }}">
                    @if(Auth()->user()->alipay)
                        <span id="alipay_hidden_image"><img style="width: 100px; height: 100px; margin-left: 50px;" src="{{ asset(Auth()->user()->alipay) }}"></span>
                        {{--<a href="##" onclick="deleteImage()">删除</a>--}}
                    @endif
                </div>
                <div style="margin-top: 18px;display: none;" id="display-card-number">
                    <label>提款账户 ： &nbsp;</label>
                    <input type="text" style="width: 250px;" name="bank_card_number" value="{{ Auth()->user()->bank_card_number }}">
                </div>

                <div style="margin-top: 18px">
                    <label>资金密码 ： &nbsp;</label>
                    <input type="password" style="width: 250px;" name="withdraw_password" value="">
                    <label style="font-size: 16px;font-weight: normal;">&nbsp;&nbsp;* 提款账户修改后 24小时内不可提现</label>
                </div>
                <div style="margin-top: 18px">
                    <label>登陆密码 ： &nbsp;</label>
                    <input type="password" style="width: 250px;" name="password" value="">
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
@section('script')
    <script>
        function deleteImage(){
            $('#alipay_hidden').val('DELETE');
            $('#alipay_hidden_image').html('');
        }

        $(function () {
            let on_selected = $('#select_bank').find("option:selected").attr('data-type');
            if(on_selected == 1){
                $('#display-alipay').css('display', 'block');
                $('#display-card-number').css('display', 'none');
            }else{
                $('#display-alipay').css('display', 'none');
                $('#display-card-number').css('display', 'block');
            }
        })

        $('#select_bank').change(function () {
            let selected = $(this).find("option:selected").attr('data-type');
            if(selected == 1){
                $('#display-alipay').css('display', 'block');
                $('#display-card-number').css('display', 'none');
            }else{
                $('#display-alipay').css('display', 'none');
                $('#display-card-number').css('display', 'block');
            }
        })
    </script>
@endsection
