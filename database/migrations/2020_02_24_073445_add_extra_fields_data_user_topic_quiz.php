<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsDataUserTopicQuiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_topic_quiz', function (Blueprint $table) {
            $table->dropColumn(['option_id','data']);
            $table->string('options_answers');
            $table->string('answered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_topic_quiz', function (Blueprint $table) {
            $table->dropColumn(['options_answers','answered']);
        });
    }
}
