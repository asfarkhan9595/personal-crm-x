<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('author')->nullable()->default('Asfar - Full Stack Developer')->after('read_time');
            $table->string('status')->default('published')->after('is_published'); // draft, scheduled, published
            $table->boolean('is_featured')->default(false)->after('status');
            $table->string('meta_title')->nullable()->after('is_featured');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('focus_keyword')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('focus_keyword');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'author',
                'status',
                'is_featured',
                'meta_title',
                'meta_description',
                'focus_keyword',
                'og_image',
            ]);
        });
    }
};

