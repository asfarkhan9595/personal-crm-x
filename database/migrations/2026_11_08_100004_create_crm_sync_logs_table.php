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
        Schema::create('crm_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 50)->nullable(); // lead, payment, batch
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action', 50); // create, update, delete, sync_all, import
            $table->string('status', 20); // success, failed, pending
            $table->text('error_message')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_sync_logs');
    }
};
