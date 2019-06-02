@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div style="padding: 10px">
        <div class="title_right">
            <strong>帮助中心</strong>
        </div>
        <div style="display: flex;height: 100%">
            <div style="float: left;min-width: 600px; width: 100%">
                <div id="accordion">

                    @foreach($helps as $help)
                        <div class="card">
                            <div class="card-header" style="padding: 4px" id="heading{{ $help->id }}">
                                <button class="btn btn-link" style="color: black; text-decoration: none" data-toggle="collapse" data-target="#collapse{{ $help->id }}" aria-expanded="true" aria-controls="collapse{{ $help->id }}">
                                    {{ $help->title }}
                                </button>
                            </div>

                            <div id="collapse{{ $help->id }}" class="collapse" aria-labelledby="heading{{ $help->id }}" data-parent="#accordion">
                                <div class="card-body">
                                    {{ $help->content }}
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </div>

@endsection
