@php use Src\language\forms\inputs\AuthInput;use Src\language\forms\links\AuthLink;use Src\language\titles\AuthTitle; @endphp
@extends('base')

@section('title', AuthTitle::RegisterTitle->translate())

@section('content')
    <div class="container">
        <form class="auth-form" hx-post="/register" hx-target="#response">
            <div class="left">
                <div class="title">{{ AuthTitle::RegisterTitle->translate() }}</div>
                <div id="response"></div>
                <input type="email" name="email" placeholder="{{ AuthInput::Email->translate() }}">
                <div class="info">{!! AuthInput::PasswordInfo->translate() !!}</div>
                <input type="password" name="password" placeholder="{{ AuthInput::Password->translate() }}">
                <input type="password" name="password_confirmation"
                       placeholder="{{ AuthInput::PasswordConfirm->translate() }}">
                <button class="main-button" type="submit">Register</button>
            </div>
            <div class="right">
                <div class="links">
                    {!! AuthLink::Login->translate() !!}
                </div>
            </div>
        </form>
    </div>
@endsection
