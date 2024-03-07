<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register__content">
    <div class="section__title">
        <h2>ログイン</h2>
    </div>
    <form class="register__form" action="{{ route('login') }}" method="post">
        @csrf
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
        <div class="form__button">
            <button class="form__button-submit" type="submit">ログイン</button>
        </div>
    </form>
    <div class="form__footer">
        <p>アカウントをお持ちでない方はこちらから</p>
        <a href="/register">会員登録</a>
    </div>
</div>
@endsection