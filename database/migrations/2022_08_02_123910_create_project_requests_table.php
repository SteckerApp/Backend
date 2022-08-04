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
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('dimension')->nullable();
            $table->longText('example_links')->nullable();
            $table->longText('example_uploads')->nullable();
            $table->longText('colors')->nullable();
            $table->longText('deliverables')->nullable();
            $table->date('date')->nullable();
            $table->enum('status', ['pending','on-going', 'in-progress', 'designer-approved', 'pm-approved','completed'])->default('pending');
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
        Schema::dropIfExists('project_requests');
    }
};
