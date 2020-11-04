<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRetakeMinimumPassingScoreChapters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->smallInteger('retakes')->default(1);
            $table->smallInteger('minimum_passing_grades')->comment('In Percentage');
            $table->smallInteger('number_of_question_in_quiz')->comment('numbers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn(['retakes','minimum_passing_grades','number_of_question_in_quiz']);
        });
    }
}
