<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 100);
            $table->string('description', 200);
            $table->string('password', 100);
            $table->smallInteger('status')->default(1)->comment('0=disabled,1=enabled');
            $table->smallInteger('is_archive')->default(0)->comment('0=not deleted,1=deleted');
            $table->integer('created_by_admin_id')->default(0)->comment('admin who created the new record first time');
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
        Schema::dropIfExists('courses');
    }
}
