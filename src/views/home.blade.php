@extends('base')

@section('title', 'Home')

@section('content')
    <div class="container"></div>
    <div hx-get="/get-profiles" hx-swap="innerHTML" hx-target=".container"
         hx-trigger="load, profile_added from:body"></div>
@endsection
