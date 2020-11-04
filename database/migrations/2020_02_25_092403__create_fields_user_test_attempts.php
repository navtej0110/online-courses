<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsUserTestAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_test_attempts', function (Blueprint $table) {
            $table->dropColumn(['test_id']);
            $table->bigInteger('module_id');
            $table->bigInteger('chapter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_test_attempts', function (Blueprint $table) {
            $table->dropColumn(['module_id','chapter_id']);
        });
    }
}
