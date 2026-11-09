<?php
namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Asfar Khan',
                'primary_role' => 'Python & AI Automation Developer',
                'secondary_roles' => ['Backend Developer', 'API Developer', 'Chrome Extension Developer'],
                'hero_supporting_text' => 'I build practical AI-powered applications, backend systems, APIs, browser extensions, and automation tools.',
                'about_title' => 'Crafting High-Performance Backend & Web Systems',
                'years_experience' => '3+',
                'about' => 'Asfar focuses on building practical software using Python, APIs, AI integrations, backend technologies, browser extensions, and automation.',
                'about_highlights' => ['Laravel Blade Server Rendering', 'Python, FastAPI & AI Workflows', 'REST API Architecture'],
            ]
        );
    }
}
