<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('course_id');
            $table->integer('test_ids');
            $table->string('course_name',100);
            $table->string('test_names',240);
            $table->string('payment_method',40)->comment('paypal,credit-card,debit-card etc');
            $table->string('payment_mode',40)->comment('manual,automatic,check,cash');
            $table->string('payment_status',40)->comment('declined,confirmed,pending');
            $table->smallInteger('payment_recurring')->default(0)->comment('0=one time, 1 = recurring');
            $table->float('payment_amount');
            $table->string('payment_currency',10);
            $table->smallInteger('duration')->comment('days');
            $table->smallInteger('status')->default(1);
            $table->smallInteger('is_archive')->default(0);
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
        Schema::dropIfExists('user_payments');
    }
}
