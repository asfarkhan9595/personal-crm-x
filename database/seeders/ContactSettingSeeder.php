<?php

namespace Database\Seeders;

use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'contact_email' => 'asfarkhan9595@gmail.com',
            'contact_whatsapp' => '+919129599595',
            'contact_location' => 'India',
            'contact_availability' => 'Open to opportunities',
            'show_email' => '1',
            'show_whatsapp' => '1',
        ];

        foreach ($defaults as $key => $value) {
            ContactSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => str_starts_with($key, 'show_') ? 'boolean' : 'text'
                ]
            );
        }
    }
}
