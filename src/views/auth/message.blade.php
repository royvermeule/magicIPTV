@extends('base')

@section('title', 'Message')

@section('content')
    <div class="container">
        <div class="auth-message">
            <p>{!! $message !!}</p>
        </div>
    </div>
@endsection
