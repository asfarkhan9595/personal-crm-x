<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            SkillSeeder::class,
            ProjectSeeder::class,
            ExperienceSeeder::class,
            PostSeeder::class,
            SocialLinkSeeder::class,
            ContactSettingSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
