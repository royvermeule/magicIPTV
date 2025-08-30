@php use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::RegisterTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/register" hx-target="#response">
            <div class="title">{{ AuthTitle::RegisterTitle->translate() }}</div>
            <div id="response"></div>
            <div class="inputs">
                <input type="email" name="email" placeholder="{{ AuthInput::Email->translate() }}">
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="password" name="password_confirmation"
                       placeholder="{{ AuthInput::PasswordConfirm->translate() }}">
            </div>
            <div class="bottom">
                <button type="submit">Register</button>
                <div class="links">
                    {!! AuthLink::Login->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection
