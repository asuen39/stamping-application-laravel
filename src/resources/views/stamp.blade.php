<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp.css') }}">
@endsection

@section('content')
<div class="stamp__content">
    <div class="stamp__title">
        {{ Auth::user()->name }}さんお疲れ様です!
    </div>
    <!-- 打刻ボタン -->
    <div class="stamp__button-content">
        <div class="stamp__button-group">
            <form action="{{ route('workStart') }}" class="stamp__button-box" method="post">
                @csrf
                <button type="submit" id="workStartBtn" name="action" class="stamp__button" {{ $isWorkStartedDisabled ? 'disabled' : '' }} value="workStart">勤務開始</button>
            </form>
            <form action="{{ route('workEnd') }}" class="stamp__button-box" method="post">
                @csrf
                <button type="submit" id="workEndBtn" name="action" class="stamp__button" {{ session('isWorkEnd') || session('isBreakStart') || session('isBreakEnd') ? '' : 'disabled' }} value="workEnd">勤務終了</button>
            </form>
        </div>
        <div class="stamp__button-group">
            <form action="{{ route('breakStart') }}" class="stamp__button-box" method="post">
                @csrf
                <button type="submit" id="breakStartBtn" name="action" class="stamp__button" value="breakStart" {{ session('isBreakStart') ? '' : 'disabled' }}>休憩開始</button>
            </form>
            <form action="{{ route('breakEnd') }}" class="stamp__button-box" method="post">
                @csrf
                <button type="submit" id="breakEndBtn" name="action" class="stamp__button" {{ session('isBreakEnd') ? '' : 'disabled' }} value="breakEnd">休憩終了</button>
            </form>
        </div>
    </div>
</div>
@endsection