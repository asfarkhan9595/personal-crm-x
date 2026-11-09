<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_role');
            $table->json('secondary_roles')->nullable();
            $table->string('hero_supporting_text')->nullable();
            $table->text('about')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
