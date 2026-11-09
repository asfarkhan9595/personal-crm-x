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
        Schema::create('crm_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->cascadeOnDelete();
            $table->date('paid_on');
            $table->string('currency', 10)->default('INR');
            $table->decimal('amount', 12, 2);
            $table->decimal('fx_rate', 12, 4)->default(1.0000); // Static locked rate, 1 for INR
            $table->decimal('received_inr', 14, 2); // amount * fx_rate
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('paid_on');
            $table->index('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_payments');
    }
};
