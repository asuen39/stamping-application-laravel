<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breaks', function (Blueprint $table) {
            /*カラム名:id, 型:bigint unsigned, PRIMARY KEY:〇 */
            $table->bigIncrements('id');
            /*カラム名:attendance_id, 型:bigint unsigned, NOT NULL:〇, FOREIGN KEY:attendances(id) */
            $table->unsignedBigInteger('attendance_id')->nullable(false);
            $table->foreign('attendance_id')->references('id')->on('attendances');
            /*カラム名:break_duration, 型:time, NOT NULL:〇 */
            $table->time('break_duration')->nullable(false);
            /*カラム名:break_out_duration, 型:time, NOT NULL: */
            $table->time('break_out_duration')->nullable();
            /*カラム名:created_at, 型:timestamp */
            $table->timestamp('created_at')->useCurrent()->nullable();
            /*カラム名:updated_at, 型:timestamp */
            $table->timestamp('updated_at')->useCurrent()->nullable();
            /* test */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breaks');
    }
}
