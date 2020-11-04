<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionOptionsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_options_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id');
            $table->integer('test_id');
            $table->string('option',100);
            $table->smallInteger('answer_boolean');
            $table->smallInteger('status')->default(1)->comment('0=disabled,1=enabled');
            $table->smallInteger('is_archive')->default(0)->comment('0=not deleted,1=deleted');
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
        Schema::dropIfExists('question_options_answers');
    }
}
