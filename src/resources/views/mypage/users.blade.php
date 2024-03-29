<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="section__title">
        <h2>ユーザーページ</h2>
    </div>
    <div class="mypage__table">
        <table class="mypage__table-inner">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                    <th>勤務表</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userValues as $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>
                        <form action="{{ route('userAttendanceList') }}" method="post">
                            @csrf <!-- CSRFトークンを追加 -->
                            <input type="hidden" name="userId" value="{{ $value->id }}">
                            <button type="submit" class="mypage__button btn--orange">勤務表</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection