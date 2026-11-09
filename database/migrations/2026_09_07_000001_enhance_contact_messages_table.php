<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'status')) {
                $table->string('status')->default('new')->after('message'); // new, read, replied, archived
            }
            if (!Schema::hasColumn('contact_messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('contact_messages', 'replied_at')) {
                $table->timestamp('replied_at')->nullable()->after('read_at');
            }
            if (!Schema::hasColumn('contact_messages', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('replied_at');
            }
            if (!Schema::hasColumn('contact_messages', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['status', 'read_at', 'replied_at', 'ip_address', 'user_agent']);
        });
    }
};

