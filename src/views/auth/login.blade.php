@php use Src\core\Session;use Src\language\buttons\CommonButton;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::LoginTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/login" hx-target="#response">
            <div class="left">
                <div class="title">{{ AuthTitle::LoginTitle->translate() }}</div>
                <div id="response"></div>
                <input id="email" value="" type="email" name="email" placeholder="{{ AuthInput::Email->translate() }}">
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
                <button class="main-button" type="submit">{{ CommonButton::LoginButton->translate() }}</button>
            </div>
            <div class="right">
                <div class="links">
                    {!! AuthLink::ForgotPassword->translate() !!}
                    {!! AuthLink::Register->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection
