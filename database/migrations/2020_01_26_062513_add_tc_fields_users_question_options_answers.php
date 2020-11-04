<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTcFieldsUsersQuestionOptionsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_question_options_answers', function (Blueprint $table) {
            $table->string('right_options',200);
            $table->smallInteger('match');
            $table->float('percentage');
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
            $table->dropColumn(['right_options', 'match', 'percentage']);
        });
    }
}
