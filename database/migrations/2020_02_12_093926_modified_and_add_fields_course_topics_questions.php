<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedAndAddFieldsCourseTopicsQuestions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('questions', function (Blueprint $table) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn(['test_questions', 'number_of_answers', 'test_id']);
                $table->bigInteger('chapter_id');
                $table->bigInteger('topic_id');
                $table->string('quiz_type')->default('main_quiz');
                $table->string('question_information')->nullable(true);
                $table->renameColumn('type', 'question_type')->comment('single_choice, multiple_choice, true/false');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }

}
