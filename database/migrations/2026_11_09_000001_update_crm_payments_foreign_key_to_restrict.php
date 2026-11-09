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
        Schema::table('crm_payments', function (Blueprint $table) {
            $table->dropForeign('crm_payments_lead_id_foreign');

            $table->foreign('lead_id')
                ->references('id')
                ->on('crm_leads')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_payments', function (Blueprint $table) {
            $table->dropForeign('crm_payments_lead_id_foreign');

            $table->foreign('lead_id')
                ->references('id')
                ->on('crm_leads')
                ->cascadeOnDelete();
        });
    }
};
