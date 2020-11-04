<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQuestionOptionsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_question_options_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('course_id');
            $table->integer('test_id');
            $table->integer('attempt_id');
            $table->smallInteger('is_archive')->default(0)->comment('0=not deleted,1=deleted');
            $table->smallInteger('answer_boolean');
            $table->text('answer_string')->comment('comma seperated words');
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
        Schema::dropIfExists('users_question_options_answers');
    }
}
