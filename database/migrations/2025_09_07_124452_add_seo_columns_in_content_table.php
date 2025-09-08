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
            $table->string('title')->nullable()->after('status');
            $table->string('meta_description', 500)->nullable()->after('title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_title')->nullable()->after('meta_keywords');
            $table->string('og_description', 500)->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('og_url')->nullable()->after('og_image');
            $table->string('og_type')->default('website')->after('og_url');
            $table->string('canonical_url')->nullable()->after('og_type');
            $table->string('robots')->default('index, follow')->after('canonical_url');
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
