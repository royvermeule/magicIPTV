@php use Src\core\Session;use Src\language\forms\buttons\AuthButton;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::ForgetPasswordTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/forgot-password-verify" hx-target="#response">
            <div class="title">{{ AuthTitle::ForgetPasswordTitle->translate() }}</div>
            <div id="response"></div>
            <div class="inputs">
                <input type="text" name="auth_code"
                       placeholder="{{ AuthInput::AuthCode->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
            </div>
            <div class="bottom">
                <button type="submit">{{ AuthButton::SendButton->translate() }}</button>
                <div class="links">
                    {!! AuthLink::Login->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection