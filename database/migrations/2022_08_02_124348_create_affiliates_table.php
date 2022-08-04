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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('referral_id');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('coupon_id')->nullable()->constrained();
            $table->integer('amount');
            $table->integer('total_amount');
            $table->foreignId('subscription_id')->constrained();
            $table->enum('status', ['active','in-active'])->nullable();
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
        Schema::dropIfExists('affiliates');
    }
};
