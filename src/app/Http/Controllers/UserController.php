<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Breaks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        //ログイン状態のチェック
        if (!Auth::check()) {
            // ログインしていない場合はログイン画面にリダイレクト
            return redirect()->route('login');
        }

        // データベースからユーザーデータを取得
        //dateカラムを起点として、現在の日付と参照してデータを取り出すようにする。
        $userValues = User::all();

        return view('mypage.users', compact('userValues'));
    }

    public function userAttendanceList(Request $request)
    {
        //ログイン状態のチェック
        if (!Auth::check()) {
            // ログインしていない場合はログイン画面にリダイレクト
            return redirect()->route('login');
        }

        $userId = $request->input('userId');

        // ユーザーIDに基づいてユーザーの勤務表データを取得し、ページネーションを適用
        $userAttendances = Attendances::where('user_id', $userId)->paginate(5);

        //------・休憩時間取得&差分計算　-------下記に続く
        $breaksDatabase = Breaks::whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
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
        foreach ($userAttendances as $attendance) {
            // 各勤務データに対して勤務時間を計算する
            $clockInTime = Carbon::parse($attendance->clock_in_time);
            $clockOutTime = Carbon::parse($attendance->clock_out_time);

            // 無効な時刻データがあればスキップ
            if ($clockInTime->isValid() && $clockOutTime->isValid()) {
                // 勤務時間を計算
                $workDuration = $clockOutTime->diffInSeconds($clockInTime);

                // 休憩時間が取得できている場合に差し引く
                if (isset($convertedBreakDurations[$attendance->id])) {
                    $breakDurationSeconds = Carbon::parse($convertedBreakDurations[$attendance->id])
                        ->hour * 3600 + Carbon::parse($convertedBreakDurations[$attendance->id])
                        ->minute * 60 + Carbon::parse($convertedBreakDurations[$attendance->id])->second;
                    $workDuration -= $breakDurationSeconds;
                }

                // 勤務時間を加算
                $hours = floor($workDuration / 3600);
                $minutes = floor(($workDuration % 3600) / 60);
                $seconds = $workDuration % 60;

                // 勤務時間をHH:mm:ss形式に変換して勤務データに追加
                $attendance->work_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }
        }

        // Bootstrapのページネーションスタイルを適用
        Paginator::useBootstrap();

        // ページネーションリンクに userId を追加
        $userAttendances->appends(['userId' => $userId]);

        return view('mypage.userAttendanceList', [
            'userAttendances' => $userAttendances,
            'convertedBreakDurations' => $convertedBreakDurations,
        ]);
    }
}
