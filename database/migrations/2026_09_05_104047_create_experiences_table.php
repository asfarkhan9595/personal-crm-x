<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_current')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
