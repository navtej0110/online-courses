<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsDatatypesUsersQuestionOptionsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_question_options_answers', function (Blueprint $table) {
            $table->dropColumn(['question_ids','answer_boolean']);
            $table->text('right_options')->change();
            $table->text('submitted_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_question_options_answers', function (Blueprint $table) {
            $table->dropColumn(['submitted_options']);
        });
    }
}
