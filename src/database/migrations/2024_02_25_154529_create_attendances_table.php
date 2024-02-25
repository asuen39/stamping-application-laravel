<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            /*カラム名:id, 型:bigint unsigned, PRIMARY KEY:〇 */
            $table->bigIncrements('id');
            /*カラム名:user_id, 型:bigint unsigned, NOT NULL:〇, FOREIGN KEY:userd(id) */
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');
            /*カラム名:date, 型:date, NOT NULL:〇 */
            $table->date('date')->nullable(false);
            /*カラム名:clock_in_time, 型:time, NOT NULL:〇 */
            $table->time('clock_in_time')->nullable(false);
            /*カラム名:clock_out_time, 型:time, NOT NULL: */
            $table->time('clock_out_time')->nullable();
            /*カラム名:created_at, 型:timestamp */
            $table->timestamp('created_at')->useCurrent()->nullable();
            /*カラム名:updated_at, 型:timestamp */
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
