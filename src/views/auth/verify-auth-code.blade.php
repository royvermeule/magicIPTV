@php use Src\core\Session;use Src\language\buttons\CommonButton;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\messages\AuthMessage;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::ForgetPasswordTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/verify-auth-code/{{ $clause }}" hx-target="#response">
            <div class="left">
                <div class="title">{{ AuthTitle::VerifyAuthCodeTitle->translate() }}</div>
                <div id="response">
                    {{ AuthMessage::AuthenticationMailSend->translate() }}
                </div>
                <input type="text" name="code"
                       placeholder="{{ AuthInput::AuthCode->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
                <button class="main-button" type="submit">{{ CommonButton::SendButton->translate() }}</button>
            </div>
            <div class="right">
                <div class="links">
                    {!! AuthLink::Login->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection