<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->boolean('status')->default(true);
            $table->string('title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description', 500)->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_url')->nullable();
            $table->string('og_type')->default('website');
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
