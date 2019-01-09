@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="display: flex;height: 100%">
        @include('layouts.left')
        <transfer amount="{{ Auth()->user()->amount }}" can_withdraw="{{ intval(Auth()->user()->amount / 100) * 100 }}"></transfer>
    </div>
@endsection
