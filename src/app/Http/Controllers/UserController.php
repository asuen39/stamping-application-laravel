<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Breaks;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    public function index()
    {
        // データベースからユーザーデータを取得
        //dateカラムを起点として、現在の日付と参照してデータを取り出すようにする。
        $userValues = User::all();

        return view('mypage.users', compact('userValues'));
    }

    public function userAttendanceList(Request $request)
    {
        $userId = $request->input('userId');

        // ユーザーIDに基づいてユーザーの勤務表データを取得し、ページネーションを適用
        $userAttendances = Attendances::where('user_id', $userId)->paginate(5);

        // Bootstrapのページネーションスタイルを適用
        Paginator::useBootstrap();

        // ページネーションリンクに userId を追加
        $userAttendances->appends(['userId' => $userId]);

        return view('mypage.userAttendanceList', [
            'userAttendances' => $userAttendances,
        ]);
    }
}
