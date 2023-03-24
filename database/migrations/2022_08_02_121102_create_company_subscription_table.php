<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_subscription', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->nullable();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('company_id')->references('id')->on('companies');
            $table->foreignId('subscription_id')->references('id')->on('subscriptions');
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('defualt', ['yes', 'no'])->default('no');
            $table->enum('type', ['monthly', 'bi-annually','annually'])->default('monthly');
            $table->integer('duration');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('status', ['active','inactive'])->default('inactive');
            $table->string('new_reference')->unique()->nullable();
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
        Schema::dropIfExists('company_subscription');
    }
};
