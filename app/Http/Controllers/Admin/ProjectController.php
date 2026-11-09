<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index() {
        $items = Project::with(['category', 'images', 'technologies'])->orderBy('sort_order')->get();
        return view("admin.projects.index", compact('items'));
    }

    public function create() {
        $categories = ProjectCategory::where('is_active', true)->orderBy('name')->get();
        $allTechnologies = Technology::orderBy('name')->get();
        return view("admin.projects.form", compact('categories', 'allTechnologies'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,mkv,avi|max:51200',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $data = $request->except(['images', 'technologies', 'new_technologies', 'cover_image_file', 'video_file']);
        $data['featured'] = $request->has('featured');
        $data['is_published'] = $request->has('is_published');
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects/covers'), $filename);
            $data['cover_image'] = 'projects/covers/' . $filename;
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects/videos'), $filename);
            $data['video_url'] = 'projects/videos/' . $filename;
        }

        $project = Project::create($data);

        // Sync Technologies
        $this->syncProjectTechnologies($project, $request);

        if ($request->hasFile('images')) {
            $firstImagePath = null;
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/projects'), $filename);
                $path = 'projects/' . $filename;
                if ($index === 0) {
                    $firstImagePath = $path;
                }
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
            if (empty($project->cover_image) && $firstImagePath) {
                $project->update(['cover_image' => $firstImagePath]);
            }
        }

        return redirect()->route("admin.projects.index")->with('success', 'Project created successfully.');
    }

    public function edit($id) {
        $item = Project::with(['images', 'category', 'technologies'])->findOrFail($id);
        $categories = ProjectCategory::where('is_active', true)
            ->orWhere('id', $item->category_id)
            ->orderBy('name')
            ->get();
        $allTechnologies = Technology::orderBy('name')->get();
        return view("admin.projects.form", compact('item', 'categories', 'allTechnologies'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,mkv,avi|max:51200',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $project = Project::findOrFail($id);
        $data = $request->except(['images', 'technologies', 'new_technologies', 'cover_image_file', 'video_file']);
        $data['featured'] = $request->has('featured');
        $data['is_published'] = $request->has('is_published');
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image_file')) {
            delete_old_upload_file($project->cover_image);
            $file = $request->file('cover_image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects/covers'), $filename);
            $data['cover_image'] = 'projects/covers/' . $filename;
        }

        if ($request->hasFile('video_file')) {
            delete_old_upload_file($project->video_url);
            $file = $request->file('video_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects/videos'), $filename);
            $data['video_url'] = 'projects/videos/' . $filename;
        }

        $project->update($data);

        // Sync Technologies
        $this->syncProjectTechnologies($project, $request);

        if ($request->hasFile('images')) {
            $firstImagePath = null;
            $existingCount = $project->images()->count();
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/projects'), $filename);
                $path = 'projects/' . $filename;
                if ($index === 0) {
                    $firstImagePath = $path;
                }
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $existingCount + $index,
                ]);
            }
            if (empty($project->cover_image) && $firstImagePath) {
                $project->update(['cover_image' => $firstImagePath]);
            }
        }

        return redirect()->route("admin.projects.index")->with('success', 'Project updated successfully.');
    }

    protected function syncProjectTechnologies(Project $project, Request $request) {
        $techIds = $request->input('technologies', []);

        if ($request->filled('new_technologies')) {
            $names = array_filter(array_map('trim', explode(',', $request->input('new_technologies'))));
            foreach ($names as $name) {
                $tech = Technology::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $techIds[] = $tech->id;
            }
        }

        $project->technologies()->sync(array_unique($techIds));
    }

    public function destroy($id) {
        $project = Project::with('images')->findOrFail($id);
        foreach ($project->images as $img) {
            delete_old_upload_file($img->image_path);
        }
        delete_old_upload_file($project->cover_image);
        delete_old_upload_file($project->video_url);
        $project->delete();
        return redirect()->route("admin.projects.index")->with('success', 'Project deleted successfully.');
    }

    public function destroyImage($id) {
        $image = ProjectImage::findOrFail($id);
        delete_old_upload_file($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function bulkDestroyImages(Request $request) {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'exists:project_images,id',
        ]);

        $images = ProjectImage::whereIn('id', $request->image_ids)->get();
        $count = $images->count();

        foreach ($images as $image) {
            delete_old_upload_file($image->image_path);
            $image->delete();
        }

        return redirect()->back()->with('success', "{$count} image(s) deleted successfully.");
    }
}
