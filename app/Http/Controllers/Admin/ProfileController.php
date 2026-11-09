<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show() {
        $item = Profile::first() ?? new Profile();
        return view("admin.profile.show", compact('item'));
    }

    public function edit() {
        $item = Profile::first() ?? new Profile();
        
        if (is_array($item->secondary_roles)) {
            $item->secondary_roles_string = implode(', ', $item->secondary_roles);
        } else {
            $item->secondary_roles_string = '';
        }

        if (is_array($item->about_highlights)) {
            $item->about_highlights_string = implode(', ', $item->about_highlights);
        } else {
            $item->about_highlights_string = $item->about_highlights ?? '';
        }

        return view("admin.profile.form", compact('item'));
    }

    public function update(Request $request) {
        $item = Profile::first() ?? new Profile();
        $data = $request->all();
        $data['show_profile_image'] = $request->has('show_profile_image');

        if ($request->hasFile('profile_image_file')) {
            delete_old_upload_file($item->profile_image);
            $file = $request->file('profile_image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $data['profile_image'] = 'profiles/' . $filename;
        }
        
        foreach (['name', 'primary_role', 'hero_supporting_text', 'about', 'about_title', 'years_experience', 'projects_count'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->normalizeText($data[$field]);
            }
        }

        if (isset($data['secondary_roles'])) {
            $roles = array_filter(array_map('trim', explode(',', $data['secondary_roles'])));
            $data['secondary_roles'] = array_values(array_map([$this, 'normalizeText'], $roles));
        }

        if (isset($data['about_highlights'])) {
            if (is_string($data['about_highlights'])) {
                $highlights = array_filter(array_map('trim', explode(',', $data['about_highlights'])));
                $data['about_highlights'] = array_values(array_map([$this, 'normalizeText'], $highlights));
            }
        }

        $item->fill($data);
        $item->save();
        return redirect()->route("admin.profile.show")->with('success', 'Profile updated successfully.');
    }

    protected function normalizeText(?string $text): ?string {
        if (!$text) return $text;
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_KC);
        }
        return str_replace(
            ['“', '”', '„', '‘', '’', '–', '—'],
            ['"', '"', '"', "'", "'", '-', '-'],
            $text
        );
    }
}
