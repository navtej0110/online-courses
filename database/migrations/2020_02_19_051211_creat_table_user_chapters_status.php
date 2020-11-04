<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatTableUserChaptersStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chapters_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('module_id');
            $table->bigInteger('chapter_id');
            $table->dateTime('started')->nullable(false);
            $table->dateTime('ended')->nullable(true);
            $table->integer('earned_time')->default(0)->comment('In Minutes');
            $table->tinyInteger('is_current')->default(0);
            $table->tinyInteger('is_completed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chapters_status');
    }
}
