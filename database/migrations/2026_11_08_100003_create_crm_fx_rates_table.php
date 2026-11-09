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
        Schema::create('crm_fx_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 10);
            $table->decimal('rate', 12, 4);
            $table->date('fetched_on');
            $table->timestamps();

            $table->unique(['from_currency', 'fetched_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_fx_rates');
    }
};
