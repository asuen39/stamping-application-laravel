<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // usersに挿入するデータを定義
        $users = [
            [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        // usersテーブルにデータを挿入
        DB::table('users')->insert($users);
    }
}
