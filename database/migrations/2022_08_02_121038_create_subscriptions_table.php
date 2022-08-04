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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->integer('price');
            $table->enum('type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->json('metadata')->nullable();
            $table->string('default')->nullable();
            $table->string('currency')->nullable();
            $table->integer('order')->nullable();
            $table->integer('user_limit')->nullable();
            $table->integer('design_limit')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
};
