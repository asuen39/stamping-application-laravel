<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // 勤怠打刻ページ。新規ユーザー登録。バリデーション定義。
        Fortify::registerView(function () {
            return view('auth.register')->with('rules', [
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users',
                'password' => 'required|string|min:8|max:191|confirmed',
                'password_confirmation' => 'required|string',
            ]);
        });

        //ログイン処理
        Fortify::authenticateUsing(function (Request $request) {
            //ログイン後のリダイレクト先
            return redirect('/');
        });
    }
}
