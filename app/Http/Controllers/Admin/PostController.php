<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest('published_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'category' => 'required|string|max:100',
            'author' => 'nullable|string|max:150',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'read_time' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'status' => 'nullable|string|in:draft,scheduled,published',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:150',
            'source_url' => 'nullable|url|max:500',
            'submit_action' => 'nullable|string',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        // Handle cover image upload
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            $coverImagePath = 'posts/' . $filename;
        }

        // Handle OG Image upload
        $ogImagePath = null;
        if ($request->hasFile('og_image')) {
            $file = $request->file('og_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts/og'), $filename);
            $ogImagePath = 'posts/og/' . $filename;
        }

        // Process tags string to array
        $tagsArray = [];
        if (!empty($validated['tags'])) {
            $tagsArray = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }

        // Action button handling
        $submitAction = $request->input('submit_action', 'publish');
        $status = $validated['status'] ?? ($submitAction === 'draft' ? 'draft' : 'published');
        if ($submitAction === 'draft') {
            $status = 'draft';
        }
        $isPublished = ($status === 'published');
        
        $publishedAt = null;
        if (!empty($validated['published_at'])) {
            $publishedAt = $validated['published_at'];
        } elseif ($isPublished) {
            $publishedAt = now();
        }

        // Auto-calculate read time if not provided
        $wordCount = str_word_count(strip_tags($validated['content']));
        $calculatedReadTime = max(1, ceil($wordCount / 200)) . ' min read';
        $readTime = !empty($validated['read_time']) ? $validated['read_time'] : $calculatedReadTime;

        Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'author' => ($validated['author'] ?? null) ?: 'Asfar - Full Stack Developer',
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'cover_image' => $coverImagePath,
            'og_image' => $ogImagePath,
            'tags' => $tagsArray,
            'read_time' => $readTime,
            'status' => $status,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'is_featured' => $request->has('is_featured') ? true : false,
            'is_trending' => $request->has('is_trending') ? true : false,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Blog post saved successfully!');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'category' => 'required|string|max:100',
            'author' => 'nullable|string|max:150',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'read_time' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'status' => 'nullable|string|in:draft,scheduled,published',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:150',
            'source_url' => 'nullable|url|max:500',
            'submit_action' => 'nullable|string',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            delete_old_upload_file($post->cover_image);
            $file = $request->file('cover_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            $post->cover_image = 'posts/' . $filename;
        }

        if ($request->hasFile('og_image')) {
            delete_old_upload_file($post->og_image);
            $file = $request->file('og_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts/og'), $filename);
            $post->og_image = 'posts/og/' . $filename;
        }

        $tagsArray = [];
        if (!empty($validated['tags'])) {
            $tagsArray = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }

        $submitAction = $request->input('submit_action', 'publish');
        $status = $validated['status'] ?? ($submitAction === 'draft' ? 'draft' : 'published');
        if ($submitAction === 'draft') {
            $status = 'draft';
        }
        $isPublished = ($status === 'published');

        $publishedAt = $post->published_at;
        if (!empty($validated['published_at'])) {
            $publishedAt = $validated['published_at'];
        } elseif ($isPublished && !$publishedAt) {
            $publishedAt = now();
        }

        // Auto-calculate read time if not provided
        $wordCount = str_word_count(strip_tags($validated['content']));
        $calculatedReadTime = max(1, ceil($wordCount / 200)) . ' min read';
        $readTime = !empty($validated['read_time']) ? $validated['read_time'] : ($post->read_time ?: $calculatedReadTime);

        $post->title = $validated['title'];
        $post->slug = $slug;
        $post->category = $validated['category'];
        $post->author = ($validated['author'] ?? null) ?: 'Asfar - Full Stack Developer';
        $post->excerpt = $validated['excerpt'] ?? null;
        $post->content = $validated['content'];
        $post->tags = $tagsArray;
        $post->read_time = $readTime;
        $post->status = $status;
        $post->is_published = $isPublished;
        $post->published_at = $publishedAt;
        $post->is_featured = $request->has('is_featured') ? true : false;
        $post->is_trending = $request->has('is_trending') ? true : false;
        $post->meta_title = $validated['meta_title'] ?? null;
        $post->meta_description = $validated['meta_description'] ?? null;
        $post->focus_keyword = $validated['focus_keyword'] ?? null;
        $post->source_url = $validated['source_url'] ?? null;

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Blog post updated successfully!');
    }

    public function destroy(Post $post)
    {
        delete_old_upload_file($post->cover_image);
        delete_old_upload_file($post->og_image);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Blog post deleted successfully!');
    }

    public function togglePublish(Post $post)
    {
        $post->is_published = !$post->is_published;
        if ($post->is_published && !$post->published_at) {
            $post->published_at = now();
        }
        $post->save();

        return back()->with('success', 'Post publication status updated!');
    }

    public function toggleTrending(Post $post)
    {
        $post->is_trending = !$post->is_trending;
        $post->save();

        return back()->with('success', 'Post trending status updated!');
    }
}

