<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Full-Stack Web Development',
                'slug' => 'full-stack-web-development',
                'description' => 'Designing and building modern, responsive, scalable web applications using Laravel, Vue/React, Tailwind CSS, and RESTful APIs.',
                'icon' => 'code-bracket',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'AI & Automation Systems',
                'slug' => 'ai-and-automation-systems',
                'description' => 'Developing AI-powered workflows, LLM integrations (OpenAI/Gemini), automated data pipelines, and custom Python microservices.',
                'icon' => 'cpu-chip',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'REST API Architecture & Backend',
                'slug' => 'rest-api-architecture-and-backend',
                'description' => 'Architecting robust, secure backend microservices, optimized database schemas (MySQL/PostgreSQL), and high-performance API endpoints.',
                'icon' => 'server-stack',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Chrome Extensions & Tools',
                'slug' => 'chrome-extensions-and-tools',
                'description' => 'Building custom Manifest V3 Chrome extensions integrated with backend APIs, AI assistants, and automated productivity tools.',
                'icon' => 'puzzle-piece',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}
