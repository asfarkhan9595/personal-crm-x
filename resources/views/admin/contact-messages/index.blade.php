@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Top Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="mail" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Contact Messages
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage inquiries, project requests, and messages submitted through your portfolio contact form.</p>
        </div>
        <a href="{{ route('admin.contact-settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-lg transition-colors border border-gray-200 dark:border-gray-600">
            <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Contact Settings
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto pb-1">
        <a href="{{ route('admin.contact-messages.index', ['status' => 'all', 'search' => request('search')]) }}" class="px-4 py-2.5 text-sm font-medium rounded-t-lg flex items-center gap-2 transition-colors border-b-2 whitespace-nowrap {{ $statusTab === 'all' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-500/10' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <span>All Messages</span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-mono">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'unread', 'search' => request('search')]) }}" class="px-4 py-2.5 text-sm font-medium rounded-t-lg flex items-center gap-2 transition-colors border-b-2 whitespace-nowrap {{ $statusTab === 'unread' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400 dark:border-emerald-400 font-bold bg-emerald-50/50 dark:bg-emerald-500/10' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <span class="flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Unread / New
            </span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-bold font-mono">{{ $counts['unread'] }}</span>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read', 'search' => request('search')]) }}" class="px-4 py-2.5 text-sm font-medium rounded-t-lg flex items-center gap-2 transition-colors border-b-2 whitespace-nowrap {{ $statusTab === 'read' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 font-bold bg-blue-50/50 dark:bg-blue-500/10' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <span>Read</span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 font-mono">{{ $counts['read'] }}</span>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'replied', 'search' => request('search')]) }}" class="px-4 py-2.5 text-sm font-medium rounded-t-lg flex items-center gap-2 transition-colors border-b-2 whitespace-nowrap {{ $statusTab === 'replied' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 font-bold bg-indigo-50/50 dark:bg-indigo-500/10' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <span>Replied</span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 font-mono">{{ $counts['replied'] }}</span>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'archived', 'search' => request('search')]) }}" class="px-4 py-2.5 text-sm font-medium rounded-t-lg flex items-center gap-2 transition-colors border-b-2 whitespace-nowrap {{ $statusTab === 'archived' ? 'border-gray-600 text-gray-600 dark:text-gray-300 dark:border-gray-400 font-bold bg-gray-100 dark:bg-gray-700' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            <span>Archived</span>
            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-mono">{{ $counts['archived'] }}</span>
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <input type="hidden" name="status" value="{{ $statusTab }}">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, subject, message..." class="w-full pl-9 pr-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Per Page & Controls -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <select name="per_page" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>Show 5 / page</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>Show 10 / page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>Show 25 / page</option>
                </select>

                @if(request('search'))
                    <a href="{{ route('admin.contact-messages.index', ['status' => $statusTab]) }}" class="p-2 text-xs font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors whitespace-nowrap" title="Clear Search">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $item->status === 'new' || !$item->is_read ? 'bg-emerald-50/30 dark:bg-emerald-950/10 font-medium' : '' }}">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                @if($item->status === 'new' || !$item->is_read)
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 flex-shrink-0" title="New Message"></span>
                                @endif
                                <span class="font-semibold">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-300">
                            <a href="mailto:{{ $item->email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                                {{ $item->email }}
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-800 dark:text-gray-200 max-w-xs truncate">
                            <span class="font-medium">{{ $item->subject ?: 'No Subject' }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'new' || !$item->is_read)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    New
                                </span>
                            @elseif($item->status === 'replied')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                                    <i data-lucide="corner-up-left" class="w-3 h-3"></i>
                                    Replied
                                </span>
                            @elseif($item->status === 'archived')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    <i data-lucide="archive" class="w-3 h-3"></i>
                                    Archived
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    Read
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-1">
                            <button type="button" onclick="openViewModal({{ $item->id }}, {{ ($item->status === 'new' || !$item->is_read) ? 'true' : 'false' }})" class="p-1.5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors" title="View Message">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="document.getElementById('deleteMsgModal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete Message">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="inbox" class="w-10 h-10 text-gray-400 mb-2"></i>
                                <p class="text-base font-medium">No contact messages found</p>
                                <p class="text-xs mt-1">There are no messages in this tab category yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $items->total() }}</span> messages
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- View Message Modals -->
    @foreach($items as $item)
    <div id="viewMsgModal-{{ $item->id }}" class="{{ session('selected_message_id') == $item->id ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <div onclick="document.getElementById('viewMsgModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div class="relative z-10 w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-start pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>{{ $item->subject ?: 'No Subject' }}</span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Received on {{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                </div>
                <button onclick="document.getElementById('viewMsgModal-{{ $item->id }}').classList.add('hidden')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Sender Info Card -->
            <div class="bg-gray-50 dark:bg-gray-700/40 p-4 rounded-xl border border-gray-200/80 dark:border-gray-700/80 mb-4 space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sender Name</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $item->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email Address</span>
                    <a href="mailto:{{ $item->email }}?subject=Re: {{ urlencode($item->subject) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium flex items-center gap-1">
                        {{ $item->email }}
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                @if($item->ip_address)
                <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 pt-1 border-t border-gray-200/60 dark:border-gray-700/60">
                    <span>IP Address</span>
                    <span class="font-mono">{{ $item->ip_address }}</span>
                </div>
                @endif
            </div>

            <!-- Message Content -->
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Message Content</label>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed max-h-60 overflow-y-auto">
                    {{ $item->message }}
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <!-- Toggle Read/Unread -->
                    <form action="{{ route('admin.contact-messages.update-status', $item->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $item->status === 'new' ? 'read' : 'new' }}">
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="{{ $item->status === 'new' ? 'check' : 'mail' }}" class="w-3.5 h-3.5"></i>
                            {{ $item->status === 'new' ? 'Mark as Read' : 'Mark as Unread' }}
                        </button>
                    </form>

                    <!-- Mark as Replied -->
                    <form action="{{ route('admin.contact-messages.update-status', $item->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="replied">
                        <button type="submit" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5 border border-indigo-200 dark:border-indigo-500/20">
                            <i data-lucide="corner-up-left" class="w-3.5 h-3.5"></i>
                            Mark as Replied
                        </button>
                    </form>

                    <!-- Archive -->
                    <form action="{{ route('admin.contact-messages.update-status', $item->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="archived">
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                            Archive
                        </button>
                    </form>
                </div>

                <!-- Direct Email Reply Button -->
                <a href="mailto:{{ $item->email }}?subject=Re: {{ urlencode($item->subject) }}" target="_blank" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-md transition-colors flex items-center gap-1.5">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    Reply Email
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteMsgModal-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <div onclick="document.getElementById('deleteMsgModal-{{ $item->id }}').classList.add('hidden')" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400 flex-shrink-0">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete Message</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Are you sure you want to permanently delete this contact message?</p>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $item->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $item->subject ?: 'No Subject' }} — {{ $item->email }}</p>
            </div>

            <div class="flex justify-end gap-3">
                <button onclick="document.getElementById('deleteMsgModal-{{ $item->id }}').classList.add('hidden')" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    Cancel
                </button>
                <form action="{{ route('admin.contact-messages.destroy', $item->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-md shadow-red-500/20">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
function openViewModal(id, isUnread) {
    const modal = document.getElementById('viewMsgModal-' + id);
    if (modal) {
        modal.classList.remove('hidden');
    }
    if (isUnread) {
        fetch(`/admin/contact-messages/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: 'read' })
        }).catch(err => console.error('Error marking message read:', err));
    }
}
</script>
@endsection

