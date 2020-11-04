<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTypeOptionIdsUsersQuestionOptionsAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_question_options_answers', function (Blueprint $table) {
            $table->string('type','40');
            $table->string('question_ids',200);
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
            $table->dropColumn(['type','question_ids']);
        });
    }
}
