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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            // $table->foreignId('subscription_id')->constrained();
            $table->string('name');
            $table->longText('colors')->nullable();
            $table->longText('description')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->longText('guideline')->nullable();
            $table->enum('status', ['active','in-active'])->default('active')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
};
