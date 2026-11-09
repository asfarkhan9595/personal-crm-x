<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Designing Autonomous AI Agents for Enterprise Web Scraping & Data Pipelines',
                'slug' => 'designing-autonomous-ai-agents-web-scraping-data-pipelines',
                'category' => 'AI & GenAI',
                'excerpt' => 'How to combine LLM function calling with headless browser automation for self-healing web scrapers and unstructured data extraction.',
                'content' => "## Web Scraping Powered by Artificial Intelligence\n\nTraditional web scrapers frequently break when target site DOM structures change. By introducing **LLM Agents** into data extraction pipelines, scrapers can dynamically adapt to markup alterations and self-heal.\n\n### Core Pipeline Steps\n- **DOM Snapshotting**: Extracting semantic HTML nodes using Playwright and Selenium.\n- **Function Calling**: Instructing models to parse target entity schemas accurately.\n- **Structured Output Validation**: Ensuring JSON output matches predefined Pydantic models.\n\n```python\nfrom pydantic import BaseModel\nfrom typing import List, Optional\n\nclass ExtractedLead(BaseModel):\n    company_name: str\n    contact_email: Optional[str]\n    tech_stack: List[str]\n```\n\nThis workflow enables fully automated, reliable data extraction pipelines that operate 24/7 with zero manual maintenance.",
                'cover_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1000&q=80',
                'tags' => ['Python', 'AI Agents', 'LLM', 'Web Scraping', 'Automation'],
                'author' => 'Asfar Khan',
                'read_time' => '8 min read',
                'status' => 'published',
                'is_published' => true,
                'is_featured' => true,
                'is_trending' => true,
                'source_url' => 'https://github.com/asfarkhan9595',
                'published_at' => now()->subHours(5),
                'views_count' => 342,
            ],
            [
                'title' => 'Claude 3.5 Sonnet vs Cursor AI: The Ultimate Developer AI Workflow Guide',
                'slug' => 'claude-sonnet-vs-cursor-ai-developer-workflow-guide',
                'category' => 'AI Tools',
                'excerpt' => 'An in-depth hands-on comparison of top AI coding tools and how to integrate them into daily backend and frontend development.',
                'content' => "## The Evolution of AI-Assisted Software Development\n\nAI dev tools have evolved from simple autocomplete snippets to multi-file context-aware pair programmers. Choosing the right AI tool combination can boost developer productivity by 3x.\n\n### Comparison Overview\n1. **Cursor AI**: Seamless workspace index, inline code edits, instant context awareness.\n2. **Claude 3.5 Sonnet**: Exceptional architectural reasoning, complex refactoring, clean code generation.\n3. **GitHub Copilot**: Lightweight completion for everyday boilerplate.\n\n```bash\n# Installing CLI AI helpers for terminal workflows\nnpm install -g @anthropic-ai/sdk\n```\n\nUtilizing multi-agent workflows and local vector databases allows developers to ship robust full-stack applications with unprecedented speed.",
                'cover_image' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=1000&q=80',
                'tags' => ['AI Tools', 'Cursor AI', 'Claude 3.5', 'Developer Productivity'],
                'author' => 'Asfar Khan',
                'read_time' => '6 min read',
                'status' => 'published',
                'is_published' => true,
                'is_featured' => false,
                'is_trending' => true,
                'source_url' => 'https://anthropic.com',
                'published_at' => now()->subDays(1),
                'views_count' => 520,
            ],
            [
                'title' => 'Building High-Concurrency Async Microservices with Python FastAPI & Redis',
                'slug' => 'building-high-concurrency-async-microservices-fastapi-redis',
                'category' => 'Programming',
                'excerpt' => 'A deep dive into structuring asynchronous Python microservices using FastAPI, Redis caching layers, and background worker queues.',
                'content' => "## Asynchronous System Architecture\n\nIn modern web engineering, building responsive backend services requires non-blocking IO and efficient resource utilization. Python's `FastAPI` framework paired with `asyncio` and `Redis` offers an extremely performant stack for handling high-concurrency workloads.\n\n### Key Highlights\n1. **Non-Blocking I/O**: Leveraging `async/await` syntax for database queries and external HTTP calls.\n2. **Redis In-Memory Caching**: Reducing database latency from milliseconds to microseconds.\n3. **Background Worker Queues**: Offloading long-running jobs or background tasks.\n\n```python\n@app.get('/api/v1/metrics')\nasync def get_system_metrics():\n    metrics = await redis.get('system_metrics')\n    if not metrics:\n        metrics = await calculate_metrics()\n        await redis.set('system_metrics', metrics, expire=60)\n    return {'status': 'success', 'data': metrics}\n```\n\nBy isolating heavy computations into asynchronous background tasks, microservices maintain steady response times under peak traffic.",
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1000&q=80',
                'tags' => ['Python', 'FastAPI', 'Redis', 'Microservices', 'Async'],
                'author' => 'Asfar Khan',
                'read_time' => '7 min read',
                'status' => 'published',
                'is_published' => true,
                'is_featured' => false,
                'is_trending' => false,
                'source_url' => null,
                'published_at' => now()->subDays(2),
                'views_count' => 210,
            ],
            [
                'title' => 'Top 10 Essential CLI & Developer Tools Every Software Engineer Needs',
                'slug' => 'top-10-essential-cli-developer-tools',
                'category' => 'Developer Tools',
                'excerpt' => 'Supercharge your terminal workflow with modern CLI utilities like ripgrep, fd, bat, zoxide, and lazydocker.',
                'content' => "## Modernizing the Command Line Experience\n\nThe traditional Unix terminal toolkit is receiving a massive upgrade thanks to Rust and modern CLI tools.\n\n### Must-Have CLI Tools\n- `rg` (ripgrep): Blazingly fast recursive text search.\n- `fd`: Intuitive, fast replacement for `find`.\n- `bat`: A `cat` clone with syntax highlighting and Git integration.\n- `lazygit` / `lazydocker`: Terminal UIs for Git and Docker management.\n\n```bash\n# Rust cargo installation\ncargo install ripgrep fd-find bat zoxide\n```\n\nAdopting these tools in your terminal workflow eliminates friction and keeps you in the flow state.",
                'cover_image' => 'https://images.unsplash.com/photo-1629654297299-c8506221ca97?auto=format&fit=crop&w=1000&q=80',
                'tags' => ['Developer Tools', 'CLI', 'Terminal', 'Productivity', 'DevOps'],
                'author' => 'Asfar Khan',
                'read_time' => '5 min read',
                'status' => 'published',
                'is_published' => true,
                'is_featured' => false,
                'is_trending' => true,
                'source_url' => 'https://github.com/BurntSushi/ripgrep',
                'published_at' => now()->subDays(3),
                'views_count' => 189,
            ],
            [
                'title' => 'Mastering Full-Stack Performance: Laravel 11 API + Vite React Frontend Optimization',
                'slug' => 'mastering-full-stack-performance-laravel-react-vite',
                'category' => 'Web Development',
                'excerpt' => 'Proven techniques for sub-second page loads: query optimization, dynamic code splitting, Redis response caching, and glassmorphism CSS tuning.',
                'content' => "## Sub-Second Web Performance Strategies\n\nCombining Laravel's RESTful API layer with a React frontend powered by Vite enables rich, desktop-grade web applications. However, maintaining high Lighthouse scores requires intentional optimization.\n\n### Key Optimization Pillars\n1. **Eager Loading**: Eliminating N+1 query problems using `\$query->with(['tags', 'author'])`.\n2. **Vite Chunk Splitting**: Lazy loading theme components and heavy charting libraries.\n3. **Gzip & Brotli Compression**: Reducing bundle sizes by over 60%.\n\n```javascript\n// React dynamic lazy import\nconst GlassTheme = React.lazy(() => import('./themes/glass/GlassLayout'));\n```\n\nWith proper caching headers and API payload trimming, full-stack applications deliver instant navigation.",
                'cover_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1000&q=80',
                'tags' => ['Laravel', 'React', 'Vite', 'Performance', 'Web Dev'],
                'author' => 'Asfar Khan',
                'read_time' => '9 min read',
                'status' => 'published',
                'is_published' => true,
                'is_featured' => false,
                'is_trending' => false,
                'source_url' => null,
                'published_at' => now()->subDays(4),
                'views_count' => 412,
            ]
        ];

        foreach ($posts as $postData) {
            Post::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }
    }
}
