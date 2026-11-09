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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->unique();
            $table->string('market')->default('India'); // India, Dubai, Other
            $table->string('service')->default('Website'); // Website, Laravel, Automation
            $table->string('contact_channel')->default('WhatsApp'); // WhatsApp, Instagram, Email, Call
            $table->string('website_url')->nullable();
            $table->text('problem_found')->nullable();
            $table->boolean('message_sent')->default(false);
            $table->string('response')->default('No response'); // No response, Interested, Not interested
            $table->date('follow_up_date')->nullable();
            $table->string('status')->default('New'); // New, Contacted, Proposal, Won, Lost
            $table->string('currency', 10)->default('INR'); // INR, AED, USD
            $table->decimal('deal_amount', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('market');
            $table->index('service');
            $table->index('follow_up_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};
