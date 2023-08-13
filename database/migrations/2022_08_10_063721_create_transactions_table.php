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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('subscription_id')->references('id')->on('subscriptions');
            $table->boolean('default')->default(false);
            $table->string('duration');
            $table->integer('unit');
            $table->string('total');
            $table->foreignId('company_id')->references('id')->on('companies');
            // $table->dateTime('payment_date')->nullable();
            // $table->dateTime('start_date')->nullable();
            // $table->dateTime('end_date')->nullable();
            // $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('currency', ['NGN', 'USD'])->default('NGN');
            $table->timestamps();
            $table->foreign('reference')->references('reference')->on('carts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
