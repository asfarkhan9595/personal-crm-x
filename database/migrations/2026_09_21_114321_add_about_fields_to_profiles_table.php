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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('about_title')->nullable()->after('about');
            $table->string('years_experience')->nullable()->after('about_title');
            $table->json('about_highlights')->nullable()->after('years_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['about_title', 'years_experience', 'about_highlights']);
        });
    }
};
