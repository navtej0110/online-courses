<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('course_id');
            $table->integer('test_id');
            $table->integer('created_by_admin_id')->default(0)->comment('admin who created the new record first time');
            $table->string('name',100);
            $table->string('description',200);
            $table->string('type',40)->comment('single choice, multiple choice, true/false');
            $table->mediumInteger('number_of_answers');
            $table->mediumInteger('test_questions');
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
        Schema::dropIfExists('questions');
    }
}
