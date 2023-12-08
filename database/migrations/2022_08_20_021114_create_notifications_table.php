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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['status','comment','attachment',''])->nullable();
            $table->enum('read', ['true','false'])->nullable();
            $table->foreignId('project_message_id')->nullable()->references('id')->on('project_messages');
            $table->foreignId('project_id')->nullable()->references('id')->on('project_requests');
            $table->foreignId('commenter_id')->nullable()->references('id')->on('users');
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
        Schema::dropIfExists('notifications');
    }
};
