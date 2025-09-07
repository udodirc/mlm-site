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
        Schema::table('content', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('og_image');
            $table->dropColumn('og_url');
            $table->dropColumn('og_type');
            $table->dropColumn('canonical_url');
            $table->dropColumn('robots');
        });
    }
};
