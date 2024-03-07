<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register__content">
    <div class="section__title">
        <h2>会員登録</h2>
    </div>
    <form class="register__form" action="{{ route('register') }}" method="post">
        @csrf
        <div class="form__group">
            <div class="form__group-content">
                <div class="form__input--text">
                    <input id="name" type="text" name="name" placeholder="名前" value="{{ old('name') }}">
                </div>
            </div>
        </div>
        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form__group">
            <div class="form__group-content">
                <div class="form__input--text">
                    <input id="email" type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
                </div>
            </div>
        </div>
        @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form__group">
            <div class="form__group-content">
                <div class="form__input--text">
                    <input id="password" type="password" name="password" placeholder="パスワード">
                </div>
            </div>
        </div>
        @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form__group">
            <div class="form__group-content">
                <div class="form__input--text">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="確認用パスワード">
                </div>
            </div>
        </div>
        @error('password_confirmation')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録</button>
        </div>
    </form>
    <div class="form__footer">
        <p>アカウントをお持ちの方はこちら</p>
        <a href="/login">ログイン</a>
    </div>
</div>
@endsection