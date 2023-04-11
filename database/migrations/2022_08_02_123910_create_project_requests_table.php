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
        Schema::create('project_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_id')->nullable();
            $table->foreign('pm_id')->references('id')->on('users');
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id')->references('id')->on('users');
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->json('dimension')->nullable();
            $table->json('example_links')->nullable();
            $table->json('example_uploads')->nullable();
            $table->json('colors')->nullable();
            $table->longText('deliverables')->nullable();
            $table->date('date')->nullable();
            $table->enum('status', ['todo','on_going', 'in_review', 'designer_approved', 'pm_approved','completed'])->default('todo');
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
        Schema::dropIfExists('project_requests');
    }
};
