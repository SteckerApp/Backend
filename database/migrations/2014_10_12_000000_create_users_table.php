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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('user_verified_at')->nullable();
            $table->string('verification_token')->nullable();
            $table->string('password');
            $table->string('user_type',100);
            $table->string('referral_code',100)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('currency',10)->nullable();
            $table->enum('desktop_notification', ['yes','no'])->default('yes');
            $table->enum('email_notification', ['never','periodically', 'instantly'])->default('instantly');
            $table->enum('notification', ['yes','no'])->default('no');
            $table->enum('status', ['active','in-active'])->default('active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
