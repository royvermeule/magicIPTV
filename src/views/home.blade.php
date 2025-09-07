@extends('base')

@section('title', 'Home')

@section('content')
    <div class="container">
        <div hx-get="/get-profiles" hx-swap="outerHTML" hx-trigger="load"></div>
    </div>
@endsection
