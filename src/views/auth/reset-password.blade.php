@php use Src\language\forms\inputs\AuthInput; @endphp
@extends('base')

@section('title', 'Reset password')

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/reset-password" hx-target="#response">
            <div class="title">Reset password</div>
            <div id="response"></div>
            <div class="inputs">
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="password" name="password_confirm"
                       placeholder="{{ AuthInput::PasswordConfirm->translate() }}">
                <input type="hidden" name="csrf_token" value="{{ Session::get('csrf_token') }}">
            </div>
            <div class="bottom">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
@endsection