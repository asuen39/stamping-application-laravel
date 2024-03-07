<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreaksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // attendancesテーブルからuser_idを取得
        $user_ids = DB::table('attendances')->pluck('user_id');

        if ($user_ids) {
            // breaksに挿入するデータを定義
            $breaks = [];
            foreach ($user_ids as $user_id) {
                $breaks[] = [
                    'attendance_id' => $user_id,
                    'break_duration' => '12:00:00',
                    'break_out_duration' => '13:00:00',
                ];
            }
            // breaksテーブルにデータを挿入
            DB::table('breaks')->insert($breaks);
        }
    }
}
