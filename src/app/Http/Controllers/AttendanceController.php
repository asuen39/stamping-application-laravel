<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Breaks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class AttendanceController extends Controller
{
    public function boot()
    {
        Paginator::useBootstrap();
    }

    /* 全従業員打刻ページ表示 */
    public function index()
    {
        //ログイン状態のチェック
        if (!Auth::check()) {
            // ログインしていない場合はログイン画面にリダイレクト
            return redirect()->route('login');
        }

        //日付と時間帯表示
        $date = Carbon::now();

        // 日付のみに加工
        //viewで日付表示用。
        $day = $date->toDateString();

        return $this->show($date, $day);
    }

    public function previousDay(Request $request)
    {
        //ログイン状態のチェック
        if (!Auth::check()) {
            // ログインしていない場合はログイン画面にリダイレクト
            return redirect()->route('login');
        }

        $currentDay = $request->route('currentDay');
        $previousDay = Carbon::parse($currentDay)->subDay()->toDateString();

        //次の日付の時間帯表示
        $date = Carbon::parse($previousDay);

        $day = $date->toDateString();

        return $this->show($date, $day);
    }

    public function nextDay(Request $request)
    {
        //ログイン状態のチェック
        if (!Auth::check()) {
            // ログインしていない場合はログイン画面にリダイレクト
            return redirect()->route('login');
        }

        $currentDay = $request->route('currentDay');
        $nextDay = Carbon::parse($currentDay)->addDay()->toDateString();

        //次の日付の時間帯表示
        $date = Carbon::parse($nextDay);

        $day = $date->toDateString();

        return $this->show($date, $day);
    }

    public function show($date, $day)
    {

        // 日付のパース
        $date = Carbon::parse($date);

        // データベースから日付ごとの勤務データを取得
        //dateカラムを起点として、現在の日付と参照してデータを取り出すようにする。
        $attendancesDatabase = Attendances::whereDate('date', $date)->get();

        //------・休憩時間取得&差分計算　-------下記に続く
        $breaksDatabase = Breaks::whereHas('attendance', function ($query) use ($date) {
            $query->whereDate('date', $date);
        })->get();

        // ユーザーごとの合計休憩時間を格納する配列を初期化
        $totalBreakDurations = [];
        $convertedBreakDurations = [];

        foreach ($breaksDatabase as $break) {
            $attendanceId = $break->attendance_id;

            // 各attendance_idに関連する休憩データを取得
            if (!isset($totalBreakDurations[$attendanceId])) {
                $totalBreakDurations[$attendanceId] = 0;
            }

            $breakStartTime = Carbon::parse($break->break_duration);
            $breakEndTime = Carbon::parse($break->break_out_duration);

            // 差分を計算して秒単位で取得
            $breakDurationSeconds = $breakEndTime->diffInSeconds($breakStartTime);

            // 合計に差分を足し合わせる
            $totalBreakDurations[$attendanceId] += $breakDurationSeconds;
        }

        // 各attendance_idごとの休憩時間を表示するための処理
        foreach ($totalBreakDurations as $attendanceId => $totalBreakDurationSeconds) {
            $hours = floor($totalBreakDurationSeconds / 3600);
            $minutes = floor(($totalBreakDurationSeconds % 3600) / 60);
            $seconds = $totalBreakDurationSeconds % 60;

            $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            // 分と秒に変換した結果を配列に追加
            $convertedBreakDurations[$attendanceId] = $formattedDuration;
        }

        //------合計勤務時間-------------
        // ユーザーごとに全ての勤務データを取得し、合計勤務時間を計算する
        $users = User::all();

        // 各勤務データに対して、ユーザーごとに合計勤務時間を計算する
        foreach ($users as $user) {
            $userId = $user->id;

            // ユーザーごとの合計勤務時間を初期化
            $userTotalWorkDurations[$userId] = '00:00:00';

            // ユーザーの全ての勤務データを取得
            $attendances = $user->attendances()->whereDate('date', $date)->get();

            foreach ($attendances as $attendance) {
                $clockInTime = Carbon::parse($attendance->clock_in_time);
                $clockOutTime = Carbon::parse($attendance->clock_out_time);

                // 無効な時刻データがあればスキップ
                if ($clockInTime->isValid() && $clockOutTime->isValid()) {
                    // 勤務時間を計算し、休憩時間を差し引く
                    $workDuration = $clockOutTime->diffInSeconds($clockInTime);

                    // 休憩時間が取得できている場合に差し引く
                    if (isset($convertedBreakDurations[$attendance->id])) {
                        $breakDurationSeconds = Carbon::parse($convertedBreakDurations[$attendance->id])
                            ->hour * 3600 + Carbon::parse($convertedBreakDurations[$attendance->id])
                            ->minute * 60 + Carbon::parse($convertedBreakDurations[$attendance->id])->second;
                        $workDuration -= $breakDurationSeconds;
                    }

                    // 分秒に変換して合計勤務時間に加算
                    $hours = floor($workDuration / 3600);
                    $minutes = floor(($workDuration % 3600) / 60);
                    $seconds = $workDuration % 60;
                    $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                    // 合計勤務時間を加算する
                    $userTotalWorkDurations[$userId] = Carbon::parse($userTotalWorkDurations[$userId])
                        ->addHours($hours)
                        ->addMinutes($minutes)
                        ->addSeconds($seconds)
                        ->format('H:i:s');
                }
            }
        }

        //ページネーション
        // データベースから、今日作成されたデータを取り出す
        $attendancesDatabase = Attendances::whereDate('date', $date)->paginate(5);

        // Bootstrapのページネーションスタイルを適用
        Paginator::useBootstrap();

        return view('attendance', [
            'day' => $day,
            'attendancesDatabase' => $attendancesDatabase,
            'convertedBreakDurations' => $convertedBreakDurations,
            'userTotalWorkDurations' => $userTotalWorkDurations,
        ]);
    }
}
