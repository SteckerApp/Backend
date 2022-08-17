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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->string('reference')->unique()->nullable();
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('duration', ['monthly', 'bi-annually','annually'])->default('monthly');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('status', ['active','inactive'])->default('inactive');
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
        Schema::dropIfExists('user_subscriptions');
    }
};
