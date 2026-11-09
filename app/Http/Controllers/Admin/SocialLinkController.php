<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index(Request $request)
    {
        $query = SocialLink::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('platform', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('icon', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort Order
        $sort = $request->input('sort', 'sort_order_asc');
        switch ($sort) {
            case 'platform_asc':
                $query->orderBy('platform', 'asc');
                break;
            case 'platform_desc':
                $query->orderBy('platform', 'desc');
                break;
            case 'sort_order_desc':
                $query->orderBy('sort_order', 'desc');
                break;
            case 'sort_order_asc':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('platform', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.social-links.index', compact('items', 'perPage'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAndNormalize($request);
        SocialLink::create($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Social link added successfully.');
    }

    public function update(Request $request, $id)
    {
        $link = SocialLink::findOrFail($id);
        $data = $this->validateAndNormalize($request);
        $link->update($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Social link updated successfully.');
    }

    protected function validateAndNormalize(Request $request): array
    {
        $rules = [
            'platform' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ];

        $validated = $request->validate($rules);
        $platform = trim($validated['platform']);
        $url = trim($validated['url']);

        // Check for Email platform or mailto link
        if (strtolower($platform) === 'email' || str_starts_with(strtolower($url), 'mailto:')) {
            if (!str_starts_with(strtolower($url), 'mailto:')) {
                if (!filter_var($url, FILTER_VALIDATE_EMAIL)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'url' => 'Please enter a valid email address or mailto: link.'
                    ]);
                }
                $url = 'mailto:' . $url;
            } else {
                $email = substr($url, 7);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'url' => 'Please enter a valid mailto: link (e.g. mailto:name@example.com).'
                    ]);
                }
            }
        } elseif (strtolower($platform) === 'whatsapp' || str_contains(strtolower($url), 'wa.me') || str_contains(strtolower($url), 'whatsapp')) {
            // Normalize WhatsApp phone number or link
            if (preg_match('/^\+?[0-9\s\-]{7,15}$/', $url)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $url);
                $url = 'https://wa.me/' . $cleanPhone;
            } else {
                if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                    $url = 'https://' . $url;
                }
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'url' => 'Please enter a valid WhatsApp link (e.g. https://wa.me/1234567890) or phone number.'
                    ]);
                }
            }
        } else {
            if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                $url = 'https://' . $url;
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'url' => 'Please enter a valid URL (e.g. https://github.com/username).'
                ]);
            }
        }

        // Auto assign icon if empty
        $icon = !empty($validated['icon']) ? strtolower(trim($validated['icon'])) : null;
        if (!$icon) {
            $platLower = strtolower($platform);
            if (str_contains($platLower, 'github')) $icon = 'github';
            elseif (str_contains($platLower, 'linkedin')) $icon = 'linkedin';
            elseif (str_contains($platLower, 'twitter') || str_contains($platLower, 'x')) $icon = 'twitter';
            elseif (str_contains($platLower, 'youtube')) $icon = 'youtube';
            elseif (str_contains($platLower, 'whatsapp') || str_contains($platLower, 'wa.me')) $icon = 'whatsapp';
            elseif (str_contains($platLower, 'email') || str_contains($platLower, 'mail')) $icon = 'mail';
            elseif (str_contains($platLower, 'website') || str_contains($platLower, 'site')) $icon = 'globe';
            else $icon = 'link';
        }

        return [
            'platform' => $platform,
            'url' => $url,
            'icon' => $icon,
            'sort_order' => isset($validated['sort_order']) ? (int)$validated['sort_order'] : 0,
            'is_active' => $request->has('is_active'),
        ];
    }

    public function toggleActive($id)
    {
        $link = SocialLink::findOrFail($id);
        $link->update(['is_active' => !$link->is_active]);

        $statusText = $link->is_active ? 'Active' : 'Inactive';
        return redirect()->back()->with('success', "'{$link->platform}' status updated to {$statusText}.");
    }

    public function destroy($id)
    {
        $link = SocialLink::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.social-links.index')->with('success', 'Social link deleted successfully.');
    }
}
