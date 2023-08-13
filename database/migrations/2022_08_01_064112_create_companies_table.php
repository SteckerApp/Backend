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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('description')->nullable();
            $table->string('hear_about_us')->nullable();
            $table->unsignedBigInteger('account_manager')->nullable();
            $table->foreign('account_manager')->references('id')->on('users');
            $table->json('card_authorization')->nullable();
            $table->enum('auto_renew', ['yes','no'])->default('yes');
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
        Schema::dropIfExists('companies');
    }
};
