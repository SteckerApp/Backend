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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['flat','percentage']);
            $table->integer('amount')->nullable();
            $table->string('currency')->nullable();
            $table->integer('recurring')->nullable();
            $table->integer('no_of_usage')->nullable();
            $table->dateTime('start');
            $table->dateTime('ends')->nullable();
            $table->enum('status', ['active','in-active'])->default('active');
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
        Schema::dropIfExists('coupons');
    }
};
