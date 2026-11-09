<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contact_settings')) {
            Schema::create('contact_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('text');
                $table->timestamps();
            });
        }

        // Migrate existing contact settings data from `settings` table if present
        if (Schema::hasTable('settings')) {
            $contactKeys = [
                'contact_email',
                'contact_whatsapp',
                'contact_linkedin',
                'contact_github',
                'contact_twitter',
                'contact_location',
                'contact_availability',
                'show_email',
                'show_whatsapp',
                'show_linkedin',
                'show_github',
                'show_twitter',
            ];

            $existing = DB::table('settings')->whereIn('key', $contactKeys)->get();
            foreach ($existing as $row) {
                DB::table('contact_settings')->updateOrInsert(
                    ['key' => $row->key],
                    [
                        'value' => $row->value,
                        'type' => $row->type ?? 'text',
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]
                );
            }

            // Clean up contact keys from general `settings` table
            DB::table('settings')->whereIn('key', $contactKeys)->delete();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};

