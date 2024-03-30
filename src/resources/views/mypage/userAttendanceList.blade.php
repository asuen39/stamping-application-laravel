<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="section__title">
        <h2>勤怠表</h2>
    </div>
    <div class="mypage__table">
        <table class="mypage__table-inner">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userAttendances as $userAttendancesView)
                <tr>
                    <td>{{ $userAttendancesView->date }}</td>
                    <td>{{ $userAttendancesView->clock_in_time->format('H:i:s') }}</td>
                    <td>{{ $userAttendancesView->clock_out_time->format('H:i:s') }}</td>
                    <td>
                        @if(isset($convertedBreakDurations[$userAttendancesView->id]))
                        {{ $convertedBreakDurations[$userAttendancesView->id] }}
                        @endif
                    </td>
                    <td>
                        {{ $userAttendancesView->work_duration }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <div class="pagination-links">
                <!-- ページネーションリンク -->
                {{ $userAttendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection