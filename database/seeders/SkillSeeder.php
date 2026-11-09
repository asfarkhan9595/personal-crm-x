<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkillCategory;
use App\Models\Skill;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            'Programming' => ['Python', 'JavaScript', 'PHP'],
            'Backend' => ['Laravel', 'REST APIs', 'MySQL'],
            'AI' => ['LLM APIs', 'OpenAI API Integration', 'AI Automation', 'Prompt Engineering'],
            'Browser / Automation' => ['Chrome Extensions', 'Browser Automation'],
            'Tools' => ['Git', 'GitHub', 'VS Code']
        ];
        
        $catOrder = 1;
        foreach($cats as $catName => $skills) {
            $cat = SkillCategory::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'sort_order' => $catOrder++]
            );
            $skillOrder = 1;
            foreach($skills as $s) {
                Skill::firstOrCreate(
                    ['slug' => Str::slug($s)],
                    ['category_id' => $cat->id, 'name' => $s, 'sort_order' => $skillOrder++, 'is_active' => true]
                );
            }
        }
    }
}
