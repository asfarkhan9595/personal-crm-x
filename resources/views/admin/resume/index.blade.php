@extends('layouts.admin')
@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Top Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Resume Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload and manage your PDF resume versions for the portfolio website.</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Active Resume Overview Banner -->
    <div class="mb-8 p-6 rounded-2xl bg-indigo-600 text-white shadow-xl relative overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #7e22ce 100%); color: #ffffff;">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold uppercase tracking-wider mb-3 text-white" style="background-color: rgba(255, 255, 255, 0.2);">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Currently Active Resume
                </div>
                @if($activeResume)
                    <h2 class="text-2xl font-bold mb-1 text-white">{{ $activeResume->title }}</h2>
                    <p class="text-xs text-indigo-100" style="color: #e0e7ff;">Uploaded on {{ $activeResume->created_at->format('M d, Y') }} &bull; PDF Document</p>
                @else
                    <h2 class="text-xl font-semibold opacity-90 text-white">No active resume uploaded yet</h2>
                    <p class="text-xs text-indigo-100" style="color: #e0e7ff;">Upload a PDF below to set your active portfolio resume.</p>
                @endif
            </div>

            @if($activeResume)
                <a href="{{ format_image_url($activeResume->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-700 hover:bg-indigo-50 text-sm font-semibold rounded-xl transition-all shadow-lg" style="background-color: #ffffff; color: #4338ca;">
                    <i data-lucide="file-down" class="w-5 h-5"></i> View / Download Active PDF
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upload Form -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700 h-fit">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upload New Resume PDF</h3>
            <form action="{{ route('admin.resume.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resume Title / Version Name *</label>
                    <input type="text" name="title" required placeholder="e.g. Asfar_Khan_Python_Developer.pdf" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PDF File *</label>
                    <input type="file" name="resume_file" accept="application/pdf" required class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg p-1">
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set as active resume immediately</label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-500/20">
                    Upload & Save Resume
                </button>
            </form>
        </div>

        <!-- History Table List -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Version Title</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                                    <span>{{ $item->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.resume.toggle-active', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Click to toggle status (Active / Inactive)">
                                        @if($item->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 cursor-pointer hover:bg-emerald-200 transition-colors">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                Inactive
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $item->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="document.getElementById('editResumeModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors" title="Edit Resume Details">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Download Button -->
                                    <a href="{{ format_image_url($item->file_path) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-md transition-colors" title="Download PDF">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.resume.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this resume file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-md transition-colors" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Resume Modal -->
                                <div id="editResumeModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left">
                                    <div onclick="document.getElementById('editResumeModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
                                    <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Resume</h3>
                                            <button onclick="document.getElementById('editResumeModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.resume.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Version Title *</label>
                                                <input type="text" name="title" value="{{ $item->title }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Replace PDF File (optional)</label>
                                                <input type="file" name="resume_file" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg p-1">
                                            </div>

                                            <div class="mb-6 flex items-center">
                                                <input type="checkbox" name="is_active" id="edit_is_active_{{ $item->id }}" value="1" {{ $item->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="edit_is_active_{{ $item->id }}" class="ml-2 block text-xs font-medium text-gray-700 dark:text-gray-300">Set Status to Active</label>
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button onclick="document.getElementById('editResumeModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/20">
                                                    Update Resume
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No resumes uploaded yet. Upload your first PDF resume!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
