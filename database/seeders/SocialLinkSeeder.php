<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        SocialLink::truncate();

        SocialLink::create([
            'platform' => 'GitHub',
            'url' => 'https://github.com/asfarkhan9595',
            'icon' => 'github',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        SocialLink::create([
            'platform' => 'LinkedIn',
            'url' => 'https://www.linkedin.com/in/asfar-khan-ai/',
            'icon' => 'linkedin',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        SocialLink::create([
            'platform' => 'Twitter / X',
            'url' => 'https://x.com/asfarkhan9595',
            'icon' => 'twitter',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        SocialLink::create([
            'platform' => 'Email',
            'url' => 'mailto:asfarkhan9595@gmail.com',
            'icon' => 'mail',
            'sort_order' => 4,
            'is_active' => true,
        ]);
    }
}
