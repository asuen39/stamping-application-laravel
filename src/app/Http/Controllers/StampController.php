<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Breaks;
use Carbon\Carbon;

class StampController extends Controller
{
    /* 従業員打刻ページ表示 */
    public function index(Request $request)
    {
        // ログインしているユーザーのIDを取得
        $userId = auth()->id();

        // セッションから勤務開始の打刻回数を取得
        $isWorkStartedCount = $request->session()->get('work_start_count', 0);

        // 打刻回数が1以上の場合は、ボタンを無効にする
        $isWorkStartedDisabled = $isWorkStartedCount > 0;

        // ログイン後の画面表示&ビューに打刻回数とボタンの無効状態を渡す
        return view('stamp')->with([
            'isWorkStartedCount' => $isWorkStartedCount,
            'isWorkStartedDisabled' => $isWorkStartedDisabled,
        ]);
    }


    /* 勤務開始ボタンアクションの処理 */
    public function workStart(Request $request)
    {
        /* 現在の時刻を取得 */
        $date = new Carbon();

        /* 時刻のみを取得 */
        $time = $date->toTimeString();

        /* データベースに保存 */
        Attendances::create([
            'user_id' => auth()->user()->id,
            'date' => now()->toDateString(),
            'clock_in_time' => $time,
        ]);

        // 勤務開始ボタンを押したらカウントする。セッションから打刻回数を取得し、1を加える
        $isWorkStartedCount = $request->session()->get('work_start_count', 0) + 1;
        // セッションに打刻回数を保存
        $request->session()->put('work_start_count', $isWorkStartedCount);

        // 休憩ボタンの$isBreakStart を true に設定する
        $isBreakStart = true;

        // 勤務終了ボタンを押したので、$isWorkEnd を true に設定する
        $isWorkEnd = true;

        return redirect('/')->with([
            'isWorkStartedCount' => $isWorkStartedCount,
            'isBreakStart' => $isBreakStart,
            'isWorkEnd' => $isWorkEnd,
        ]);
    }

    /* 勤務終了 */
    public function workEnd(Request $request)
    {
        /* 現在の時刻を取得 */
        $date = new Carbon();

        /* 時刻のみを取得 */
        $time = $date->toTimeString();

        /* データベースに保存 */
        // ログイン中のユーザーの最後の打刻レコードを取得する
        $attendance = Attendances::where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        // 最後の打刻レコードが存在する場合、そこに勤務終了時間を追加する
        if ($attendance) {
            $attendance->update(['clock_out_time' => $time]);
        } else {
            // 最初の打刻が行われていない場合はエラーを返すか、何かしらの処理を行う
            return response()->json(['message' => 'No record found'], 404);
        }

        return redirect('/');
    }

    /* 休憩開始 */
    public function breakStart(Request $request)
    {
        // ログイン中のユーザーの最後の打刻レコードを取得する
        $attendance = Attendances::where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        if ($attendance) {
            $attendanceId = $attendance->id; // Attendancesテーブルのuser_id

            /* 現在の時刻を取得 */
            $date = new Carbon();

            /* 時刻のみを取得 */
            $time = $date->toTimeString();

            /* データベースに保存 */
            Breaks::create([
                'attendance_id' => $attendanceId,
                'break_duration' => $time, // 休憩開始時刻を登録
            ]);
        }

        // 休憩終了ボタンの$isBreakEnd をtrue に設定する
        $isBreakEnd = true;

        return redirect('/')->with([
            'isBreakEnd' => $isBreakEnd,
        ]);
    }


    /* 休憩終了 */
    public function breakEnd(Request $request)
    {
        // 直近の勤務記録のIDを取得する
        $latestAttendanceId = Attendances::where('user_id', auth()->user()->id)
            ->latest()
            ->value('id');

        // 直近の勤務記録のIDが取得できた場合
        if ($latestAttendanceId) {
            // 直近の勤務記録に関連する最後の休憩レコードを取得する
            $break = Breaks::where('attendance_id', $latestAttendanceId)
                ->latest()
                ->first();

            // 直近の休憩レコードが取得できた場合
            if ($break) {
                // 現在の時刻を取得
                $time = now()->toTimeString();

                // 休憩終了時刻を更新
                $break->update(['break_out_duration' => $time]);
            } else {
                // 直近の休憩レコードが存在しない場合はエラーを返すか、何かしらの処理を行う
                return response()->json(['message' => 'No record found'], 404);
            }
        }

        // 勤務終了ボタンを押したので、$isWorkEnd を true に設定する
        $isWorkEnd = true;

        //休憩開始ボタンを再度実行可能にする。
        $isBreakStart = true;

        return redirect('/')->with([
            'isWorkEnd' => $isWorkEnd,
            'isBreakStart' => $isBreakStart,
        ]);
    }
}
