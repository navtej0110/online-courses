<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('course_id');
            $table->integer('created_by_admin_id')->default(0)->comment('admin who created the new record first time');
            $table->string('name',100);
            $table->string('description',200);
            $table->smallInteger('time_limit')->default(0)->comment('0=false,1=true');
            $table->mediumInteger('idle_time')->default(0)->comment('0=false,1=true');
            $table->mediumInteger('number_of_questions')->default(0)->comment('0=false,1=true');
            $table->smallInteger('scatter')->default(0)->comment('0=false,1=true');
            $table->mediumText('content');
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
        Schema::dropIfExists('tests');
    }
}
