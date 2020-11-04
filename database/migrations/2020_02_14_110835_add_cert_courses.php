<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->smallInteger('include_certificate')->default(0);
            $table->smallInteger('is_beginner')->default(0);
            $table->smallInteger('is_intermediate')->default(0);
            $table->smallInteger('is_advanced')->default(0);
            $table->float('price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['include_certificate','is_beginner','is_intermediate','is_advanced','price']);
        });
    }
}
