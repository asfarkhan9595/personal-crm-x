<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('problem')->nullable();
            $table->longText('solution')->nullable();
            $table->longText('architecture')->nullable();
            $table->longText('challenges')->nullable();
            $table->longText('what_i_learned')->nullable();
            $table->string('live_demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
