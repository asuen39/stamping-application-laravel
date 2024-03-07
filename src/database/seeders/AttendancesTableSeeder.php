<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザーのIDを取得
        $user = DB::table('users')->where('name', 'テスト太郎')->first();

        if ($user) {
            // attendancesに挿入するデータを定義
            $attendances = [
                [
                    'user_id' => $user->id,
                    'date' => '2024-02-26',
                    'clock_in_time' => '10:00:00',
                    'clock_out_time' => '20:00:00',
                ],
            ];

            // attendancesテーブルにデータを挿入
            DB::table('attendances')->insert($attendances);
        }
    }
}
