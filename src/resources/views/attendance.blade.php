<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance__content">
    <div class="attendance__day">
        <form action="{{ route('previous_day', ['currentDay' => $day]) }}" method="GET">
            @csrf
            <button type="submit" class="attendance__button"><span class="dli-chevron-round-left"></span></button>
        </form>
        {{ $day }}
        <form action="{{ route('next_day', ['currentDay' => $day]) }}" method="GET">
            @csrf
            <button type="submit" class="attendance__button"><span class="dli-chevron-round-right"></span></button>
        </form>
    </div>
    <div class="attendance__table">
        <table class="attendance__table-inner">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                <!--$attendancesDatabaseを新規変数$attendancesViewに置き換える。 foreeachを実行する。-->
                @foreach ($attendancesDatabase as $attendancesView)
                <tr>
                    <td>{{ $attendancesView->user->name }}</td>
                    <td>{{ $attendancesView->clock_in_time->format('H:i:s') }}</td>
                    <td>{{ $attendancesView->clock_out_time->format('H:i:s') }}</td>
                    <td>
                        @if(isset($convertedBreakDurations[$attendancesView->id]))
                        {{ $convertedBreakDurations[$attendancesView->id] }}
                        @endif
                    </td>
                    <td>
                        @if (isset($userTotalWorkDurations[$attendancesView->user_id]))
                        {{ $userTotalWorkDurations[$attendancesView->user_id] }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <div class="pagination-links">
                <!-- ページネーションリンク -->
                {{ $attendancesDatabase->links() }}
            </div>
        </div>
    </div>
</div>
@endsection