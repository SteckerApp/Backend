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
            $table->enum('type', ['monthly', 'bi-annually', 'quarterly', 'annually'])->default('monthly');
            $table->integer('days')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('default')->default(false);
            $table->boolean('info')->default(false);
            $table->string('discounted')->nullable();
            $table->enum('currency', ['NGN', 'USD'])->default('NGN');
            $table->enum('visible', ['yes', 'no'])->default('yes');
            $table->enum('most_popular', ['yes', 'no'])->default('no');
            $table->integer('order')->nullable();
            $table->integer('user_limit')->nullable();
            $table->integer('design_limit')->nullable();
            $table->string('category')->default('main');
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
