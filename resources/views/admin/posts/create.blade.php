@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="pen-tool" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                CREATE BLOG POST
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Compose and publish daily technical articles, system architecture logs, and tutorials.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-xs font-semibold rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Back to Posts
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 text-xs">
            <div class="font-semibold mb-1 flex items-center gap-1.5"><i data-lucide="alert-circle" class="w-4 h-4"></i> Please fix the following errors:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Create Form -->
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="blogPostForm">
        @csrf

        <!-- 1. BASIC INFORMATION CARD -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="info" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">Basic Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Title *</label>
                    <input type="text" id="postTitle" name="title" value="{{ old('title') }}" required placeholder="e.g. Building Scalable Microservices with FastAPI & Redis" class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Slug</label>
                        <button type="button" onclick="generateSlug()" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-3 h-3"></i> Auto-Generate Slug
                        </button>
                    </div>
                    <div class="flex items-center">
                        <span class="px-3 py-2.5 text-xs bg-gray-100 dark:bg-gray-600/60 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-xl text-gray-500 dark:text-gray-400 font-mono">/blog/</span>
                        <input type="text" id="postSlug" name="slug" value="{{ old('slug') }}" placeholder="building-scalable-microservices" class="w-full px-3 py-2.5 text-xs rounded-r-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-medium">
                        @php
                            $categories = [
                                'AI & GenAI',
                                'AI Tools',
                                'Developer Tools',
                                'Apps & Software',
                                'Tech Trends',
                                'Web Development',
                                'Programming',
                                'Developer Productivity',
                                'Tutorials & How-To',
                                'Reviews & Comparisons',
                                'Developer Career'
                            ];
                        @endphp
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', 'AI & GenAI') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Source URL -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Source / External URL (Optional)</label>
                    <input type="url" name="source_url" value="{{ old('source_url') }}" placeholder="https://github.com/... or https://techcrunch.com/..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Tags (Comma-separated)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Python, FastAPI, Redis, Architecture" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                </div>

                <!-- Author -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Author</label>
                    <input type="text" name="author" value="{{ old('author', 'Asfar - Full Stack Developer') }}" placeholder="Asfar - Full Stack Developer" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Read Time -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Read Time Estimate (Optional)</label>
                    <input type="text" name="read_time" value="{{ old('read_time') }}" placeholder="e.g. 5 min read (Auto-calculated if blank)" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                </div>
            </div>
        </div>

        <!-- 2. FEATURED IMAGE CARD -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="image" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">Featured Image</h2>
            </div>

            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-center bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100/50 dark:hover:bg-gray-700/40 transition-colors">
                    <i data-lucide="upload-cloud" class="w-10 h-10 text-gray-400 mx-auto mb-2"></i>
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Click to upload header featured image</p>
                    <p class="text-[11px] text-gray-400 mb-3">PNG, JPG, WEBP or GIF up to 3MB</p>
                    <input type="file" name="cover_image" id="coverImageInput" accept="image/*" onchange="previewFeaturedImage(this)" class="block w-full max-w-xs mx-auto text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                </div>

                <!-- Preview Box -->
                <div id="imagePreviewContainer" class="hidden relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-900 max-w-md mx-auto">
                    <img id="featuredImagePreview" src="" alt="Preview" class="w-full h-48 object-cover opacity-90">
                    <button type="button" onclick="removeFeaturedImage()" class="absolute top-2 right-2 p-1.5 bg-red-600/80 hover:bg-red-600 text-white rounded-lg backdrop-blur shadow transition-colors" title="Remove Image">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 px-2.5 py-1 bg-black/60 backdrop-blur rounded text-[11px] text-white font-mono">Featured Image Preview</div>
                </div>
            </div>
        </div>

        <!-- 3. EXCERPT & ARTICLE CONTENT CARD -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="file-text" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">Excerpt & Article Content</h2>
            </div>

            <div class="space-y-5">
                <!-- Excerpt -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Excerpt (Short Summary)</label>
                    <textarea name="excerpt" rows="2" placeholder="Brief 1-2 sentence article preview for post cards..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none">{{ old('excerpt') }}</textarea>
                </div>

                <!-- Article Content with Rich Toolbar -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Article Content * (Rich Markdown Editor)</label>
                        <button type="button" onclick="togglePreview()" id="previewToggleBtn" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Show Live Preview
                        </button>
                    </div>

                    <!-- Formatting Toolbar -->
                    <div class="bg-gray-100 dark:bg-gray-700/60 p-2.5 rounded-t-xl border border-gray-300 dark:border-gray-600 border-b-0 flex flex-wrap items-center gap-1.5 text-xs">
                        <button type="button" onclick="insertSyntax('**', '**')" class="px-2.5 py-1 font-bold rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Bold text">
                            B
                        </button>
                        <button type="button" onclick="insertSyntax('*', '*')" class="px-2.5 py-1 italic font-serif rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Italic text">
                            I
                        </button>
                        <div class="h-4 w-[1px] bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        <button type="button" onclick="insertSyntax('\n## ', '\n')" class="px-2.5 py-1 font-bold rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Heading 2">
                            H2
                        </button>
                        <button type="button" onclick="insertSyntax('\n### ', '\n')" class="px-2.5 py-1 font-bold text-xs rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Heading 3">
                            H3
                        </button>
                        <div class="h-4 w-[1px] bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        <button type="button" onclick="insertSyntax('\n```python\n', '\n```\n')" class="px-2.5 py-1 font-mono rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600 flex items-center gap-1" title="Code Block">
                            <i data-lucide="code" class="w-3 h-3"></i> Code
                        </button>
                        <button type="button" onclick="insertSyntax('`', '`')" class="px-2 py-1 font-mono text-[11px] rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Inline Code">
                            `inline`
                        </button>
                        <button type="button" onclick="insertSyntax('\n> ', '\n')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Quote Callout">
                            “ ” Quote
                        </button>
                        <div class="h-4 w-[1px] bg-gray-300 dark:bg-gray-600 mx-1"></div>
                        <button type="button" onclick="insertSyntax('\n- ', '\n')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Bullet List">
                            • List
                        </button>
                        <button type="button" onclick="insertSyntax('\n1. ', '\n')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Numbered List">
                            1. List
                        </button>
                        <button type="button" onclick="insertSyntax('[', '](https://example.com)')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600 flex items-center gap-1" title="Add Link">
                            <i data-lucide="link" class="w-3 h-3"></i> Link
                        </button>
                        <button type="button" onclick="insertSyntax('\n---\n', '')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 shadow-sm border border-gray-200 dark:border-gray-600" title="Divider Line">
                            — Divider
                        </button>
                    </div>

                    <!-- Textarea Editor -->
                    <textarea id="articleContent" name="content" rows="14" required placeholder="Write your full technical article content here using Markdown..." class="w-full px-4 py-3 text-xs rounded-b-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono leading-relaxed focus:ring-2 focus:ring-indigo-500">{{ old('content') }}</textarea>

                    <!-- Live Preview Panel -->
                    <div id="previewContainer" class="hidden mt-3 p-5 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-indigo-500 mb-2 flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Live Article Preview
                        </div>
                        <div id="previewBox" class="prose dark:prose-invert max-w-none text-xs text-gray-800 dark:text-gray-200 leading-relaxed font-sans whitespace-pre-wrap"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SEO SETTINGS CARD (COLLAPSIBLE) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <button type="button" onclick="toggleSection('seoSection', 'seoArrow')" class="w-full p-6 sm:p-7 flex items-center justify-between text-left hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex items-center gap-2">
                    <i data-lucide="search" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">SEO Settings</h2>
                </div>
                <i data-lucide="chevron-down" id="seoArrow" class="w-5 h-5 text-gray-400 transition-transform duration-200"></i>
            </button>

            <div id="seoSection" class="p-6 sm:p-7 pt-0 border-t border-gray-100 dark:border-gray-700 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-5">
                    <!-- Meta Title -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="SEO Title for search engines..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Focus Keyword -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Focus Keyword</label>
                        <input type="text" name="focus_keyword" value="{{ old('focus_keyword') }}" placeholder="e.g. FastAPI Redis Microservices" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Meta Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Brief summary for Google search snippet (150-160 characters)..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none">{{ old('meta_description') }}</textarea>
                    </div>

                    <!-- OG Image -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Social Share Image (OG Image)</label>
                        <input type="file" name="og_image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-200">
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. PUBLISHING CARD (COLLAPSIBLE) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <button type="button" onclick="toggleSection('publishingSection', 'publishingArrow')" class="w-full p-6 sm:p-7 flex items-center justify-between text-left hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex items-center gap-2">
                    <i data-lucide="send" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wide">Publishing & Visibility</h2>
                </div>
                <i data-lucide="chevron-down" id="publishingArrow" class="w-5 h-5 text-gray-400 transition-transform duration-200"></i>
            </button>

            <div id="publishingSection" class="p-6 sm:p-7 pt-0 border-t border-gray-100 dark:border-gray-700 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-5">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Publication Status</label>
                        <select name="status" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-medium">
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <!-- Publish Date -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                    </div>

                    <!-- Featured Post & Trending Article -->
                    <div class="md:col-span-2 pt-2 flex flex-wrap items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">Pin as Featured Post on Blog Homepage</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_trending" id="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">🔥 Pin as Trending Article</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. ACTION BUTTONS FOOTER -->
        <div class="sticky bottom-4 z-20 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md p-4 sm:p-5 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
            <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                Cancel
            </a>

            <div class="flex items-center gap-3">
                <button type="submit" name="submit_action" value="draft" class="px-5 py-2.5 text-xs font-semibold bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-xl transition-colors flex items-center gap-2">
                    <i data-lucide="file-minus" class="w-4 h-4"></i> Save Draft
                </button>
                
                <button type="submit" name="submit_action" value="publish" class="px-6 py-2.5 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Publish Article
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto Generate Slug
    function generateSlug() {
        const titleInput = document.getElementById('postTitle');
        const slugInput = document.getElementById('postSlug');
        if (titleInput && slugInput) {
            let slug = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    }

    document.getElementById('postTitle')?.addEventListener('input', function() {
        const slugInput = document.getElementById('postSlug');
        if (slugInput && !slugInput.value) {
            generateSlug();
        }
    });

    // Image Preview
    function previewFeaturedImage(input) {
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('featuredImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFeaturedImage() {
        const input = document.getElementById('coverImageInput');
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('featuredImagePreview');
        if (input) input.value = '';
        if (preview) preview.src = '';
        if (container) container.classList.add('hidden');
    }

    // Toggle Collapsible Sections
    function toggleSection(sectionId, arrowId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById(arrowId);
        if (section && arrow) {
            section.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    }

    // Markdown Toolbar
    function insertSyntax(before, after) {
        const textarea = document.getElementById('articleContent');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        const replacement = before + (selectedText || 'text') + after;

        textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + before.length, start + before.length + (selectedText || 'text').length);
        updatePreview();
    }

    function togglePreview() {
        const previewContainer = document.getElementById('previewContainer');
        const btn = document.getElementById('previewToggleBtn');
        if (previewContainer.classList.contains('hidden')) {
            previewContainer.classList.remove('hidden');
            btn.innerHTML = '<i data-lucide="eye-off" class="w-3.5 h-3.5"></i> Hide Live Preview';
            updatePreview();
        } else {
            previewContainer.classList.add('hidden');
            btn.innerHTML = '<i data-lucide="eye" class="w-3.5 h-3.5"></i> Show Live Preview';
        }
        if (window.lucide) window.lucide.createIcons();
    }

    function updatePreview() {
        const textarea = document.getElementById('articleContent');
        const previewBox = document.getElementById('previewBox');
        if (textarea && previewBox) {
            previewBox.textContent = textarea.value || 'Nothing written yet...';
        }
    }

    document.getElementById('articleContent')?.addEventListener('input', updatePreview);
</script>
@endsection
