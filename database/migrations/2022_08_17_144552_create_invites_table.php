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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('role')->nullable();
            $table->foreignId('invite_by')->references('id')->on('users');
            $table->foreignId('company_id')->nullable()->references('id')->on('companies');
            $table->json('preset')->nullable();
            $table->string('status')->default('pending');
            $table->string('platform')->default('new-user');
            $table->json('payment')->nullable();
            $table->enum('type', ['admin','client', 'affiliate'])->default('client');
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
        Schema::dropIfExists('invites');
    }
};
