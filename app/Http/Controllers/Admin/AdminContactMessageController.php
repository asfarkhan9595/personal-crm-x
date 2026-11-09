<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Counts for tabs & dashboard
        $counts = [
            'all' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'new')->orWhere('is_read', false)->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'archived' => ContactMessage::where('status', 'archived')->count(),
        ];

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status Filter Tab
        $statusTab = $request->input('status', 'all');
        if ($statusTab === 'unread') {
            $query->where(function($q) {
                $q->where('status', 'new')->orWhere('is_read', false);
            });
        } elseif (in_array($statusTab, ['read', 'replied', 'archived'])) {
            $query->where('status', $statusTab);
        }

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 1 || $perPage > 100) $perPage = 10;

        $items = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('admin.contact-messages.index', compact('items', 'counts', 'statusTab', 'perPage'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if ($message->status === 'new' || !$message->is_read) {
            $message->update([
                'status' => 'read',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return redirect()->route('admin.contact-messages.index', ['status' => request('status', 'all')])
            ->with('selected_message_id', $message->id);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:new,read,replied,archived'
        ]);

        $newStatus = $request->input('status');
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'new') {
            $updateData['is_read'] = false;
        } else {
            $updateData['is_read'] = true;
            if (!$message->read_at) {
                $updateData['read_at'] = now();
            }
        }

        if ($newStatus === 'replied' && !$message->replied_at) {
            $updateData['replied_at'] = now();
        }

        $message->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Message status updated to " . ucfirst($newStatus) . "."]);
        }

        return redirect()->back()->with('success', "Message status updated to " . ucfirst($newStatus) . ".");
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Contact message deleted successfully.');
    }
}

