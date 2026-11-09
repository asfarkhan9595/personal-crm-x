<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'projects_count')) {
                $table->string('projects_count')->nullable()->after('years_experience');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'projects_count')) {
                $table->dropColumn('projects_count');
            }
        });
    }
};

