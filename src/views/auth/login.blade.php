@php use Src\core\Session;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::LoginTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/login" hx-target="#response">
            <div class="title">{{ AuthTitle::LoginTitle->translate() }}</div>
            <div id="response"></div>
            <div class="inputs">
                <input type="email" name="email" placeholder="{{ AuthInput::Email->translate() }}">
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
            </div>
            <div class="bottom">
                <button type="submit">Login</button>
                <div class="links">
                    {!! AuthLink::ForgotPassword->translate() !!}
                    {!! AuthLink::Register->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection
