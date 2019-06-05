@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <device equipment='{{ Auth()->user()->equipment }}'></device>
@endsection
