@php use Src\core\Session;use Src\language\forms\buttons\AuthButton;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::ForgetPasswordTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/forgot-password-send" hx-target="#response">
            <div class="title">{{ AuthTitle::ForgetPasswordTitle->translate() }}</div>
            <div class="response"></div>
            <div class="inputs">
                <input type="email" name="email" value="{{ $email }}"
                       placeholder="{{ AuthInput::Email->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
            </div>
            <div class="bottom">
                <button type="submit">{{ AuthButton::ForgetPasswordButton->translate() }}</button>
                <div class="links">
                    {!! AuthLink::Login->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection