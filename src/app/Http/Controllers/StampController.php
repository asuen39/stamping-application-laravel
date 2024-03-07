<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Breaks;
use Carbon\Carbon;

class StampController extends Controller
{
    /* 従業員打刻ページ表示 */
    public function index()
    {
        // ログインしているユーザーのIDを取得
        $userId = auth()->user()->id;

        // 直近の勤務記録を取得
        $latestAttendance = Attendances::where('user_id', $userId)->latest()->first();

        // 勤務終了が登録されているかどうかをチェック
        if ($latestAttendance) {
            // 直近の勤務記録が存在する場合
            if (!is_null($latestAttendance->clock_out_time)) {
                // 勤務終了が登録されている場合
                $isWorkEnded = true;
            } else {
                // 勤務終了が登録されていない場合
                $isWorkEnded = false;
            }
        } else {
            // 直近の勤務記録が存在しない場合
            $isWorkEnded = false;
        }

        // 勤務開始ボタンが押されたかどうかをチェック
        if ($latestAttendance && is_null($latestAttendance->clock_out_time)) {
            // 直近の勤務記録が存在し、かつ勤務終了が登録されていない場合
            $isWorkStarted = true;
        } else {
            // 直近の勤務記録が存在しないか、勤務終了が登録されている場合
            $isWorkStarted = false;
        }

        // 休憩開始がされたかチェック。
        $isBreakStarted = Breaks::where('attendance_id', $userId)->whereNotNull('break_duration')->whereNull('break_out_duration')->exists();

        // 休憩終了ボタンが押されたかどうかをチェック
        $isBreakEnded = !Breaks::where('attendance_id', $userId)
            ->whereNotNull('break_duration')
            ->whereNull('break_out_duration')
            ->exists();

        //ログイン後の画面表示
        return view('stamp', compact('isWorkEnded', 'isWorkStarted', 'isBreakStarted', 'isBreakEnded'));
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

        /*JSON形式のレスポンスを作成。response()->json()は、Laravelの関数で、指定されたデータをJSON形式に変換してHTTPレスポンスとして送信します。第一引数には、レスポンスに含めるデータを連想配列として指定します。ここでは、'message' => 'success'という連想配列を指定しています。これは、レスポンスに含まれるキーが'message'で、その値が'success'であることを意味します。第二引数の200は、HTTPステータスコードを指定しています。200は「成功」を意味します。つまり、このコードは、レスポンスとして成功メッセージを含むJSONを返すことを表しています。 */
        return redirect('/');
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
            $attendanceId = $attendance->user_id; // Attendancesテーブルのuser_id

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

        return redirect('/');
    }


    /* 休憩終了 */
    public function breakEnd(Request $request)
    {
        /* 現在の時刻を取得 */
        $time = now()->toTimeString();

        // ログイン中のユーザーのBreaksテーブル最後の打刻レコードを取得する
        $breaks = Breaks::where('attendance_id', auth()->user()->id)
            ->latest()
            ->first();

        if ($breaks) {
            $breaks->update(['break_out_duration' => $time]);
        } else {
            // 最初の打刻が行われていない場合はエラーを返すか、何かしらの処理を行う
            return response()->json(['message' => 'No record found'], 404);
        }

        return redirect('/');
    }
}
