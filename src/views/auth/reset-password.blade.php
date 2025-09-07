@php use Src\core\Session;use Src\language\buttons\CommonButton;use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink; @endphp
@extends('base')

@section('title', 'Reset password')

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/reset-password" hx-target="#response">
            <div class="left">
                <div class="title">Reset password</div>
                <div id="response"></div>
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="password" name="password_confirmation"
                       placeholder="{{ AuthInput::PasswordConfirm->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
                <button class="main-button" type="submit">{{ CommonButton::SendButton->translate() }}</button>
            </div>
            <div class="right">
                {!! AuthLink::Login->translate() !!}
            </div>
        </form>
    </div>
@endsection