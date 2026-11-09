<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function index() {
        $items = Resume::orderBy('created_at', 'desc')->get();
        $activeResume = Resume::where('is_active', true)->first();
        return view("admin.resume.index", compact('items', 'activeResume'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'resume_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('resume_file')) {
            $file = $request->file('resume_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/resumes'), $filename);
            $path = 'resumes/' . $filename;

            $isActive = $request->has('is_active') || Resume::count() === 0;
            if ($isActive) {
                Resume::query()->update(['is_active' => false]);
            }

            Resume::create([
                'title' => $request->title,
                'file_path' => $path,
                'is_active' => $isActive,
            ]);

            return redirect()->route('admin.resume.index')->with('success', 'Resume PDF uploaded successfully.');
        }

        return redirect()->back()->withErrors(['resume_file' => 'Please select a valid PDF file.']);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'resume_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $resume = Resume::findOrFail($id);
        $data = ['title' => $request->title];

        if ($request->hasFile('resume_file')) {
            delete_old_upload_file($resume->file_path);
            $file = $request->file('resume_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/resumes'), $filename);
            $data['file_path'] = 'resumes/' . $filename;
        }

        $isActive = $request->has('is_active');
        if ($isActive && !$resume->is_active) {
            Resume::query()->update(['is_active' => false]);
            $data['is_active'] = true;
        } elseif (!$isActive && $resume->is_active) {
            $data['is_active'] = false;
        }

        $resume->update($data);

        return redirect()->route('admin.resume.index')->with('success', 'Resume updated successfully.');
    }

    public function toggleActive($id) {
        $resume = Resume::findOrFail($id);

        if ($resume->is_active) {
            $resume->update(['is_active' => false]);
            $msg = "'{$resume->title}' is now Inactive.";
        } else {
            Resume::query()->update(['is_active' => false]);
            $resume->update(['is_active' => true]);
            $msg = "'{$resume->title}' is now set as Active.";
        }

        return redirect()->route('admin.resume.index')->with('success', $msg);
    }

    public function destroy($id) {
        $resume = Resume::findOrFail($id);
        delete_old_upload_file($resume->file_path);
        $resume->delete();

        if (!Resume::where('is_active', true)->exists()) {
            $latest = Resume::latest()->first();
            if ($latest) {
                $latest->update(['is_active' => true]);
            }
        }

        return redirect()->route('admin.resume.index')->with('success', 'Resume deleted successfully.');
    }
}
