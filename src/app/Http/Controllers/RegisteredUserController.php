<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistrationCompletedMail;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
    /* 新規ユーザー登録画面表示 */
    public function create()
    {
        return view('auth.register');
    }

    /* ユーザーを登録 */
    public function store(Request $request)
    {
        // 新規ユーザー作成アクション機能を取得
        $createNewUserAction = app(CreatesNewUsers::class);

        // ユーザーを作成するアクション機能を呼び出してユーザーを登録
        $user = $createNewUserAction->create($request->all());

        // ユーザーにメールを送信
        $loginUrl = URL::to('/login');
        Mail::to($user->email)->send(new UserRegistrationCompletedMail($user, $loginUrl));

        // ログインページにリダイレクト
        return redirect()->route('login');
    }
}
