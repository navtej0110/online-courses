<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',100)->nullable(false);
            $table->string('video_1')->nullable(true);
            $table->string('video_2')->nullable(true);
            $table->string('video_3')->nullable(true);
            $table->text('content')->nullable(true);
            $table->text('key_learnings')->nullable(true);
            $table->text('check_your_knowledge')->nullable(true);
            $table->smallInteger('status')->default(1);
            $table->smallInteger('is_archive')->default(0);
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
        Schema::dropIfExists('topics');
    }
}
