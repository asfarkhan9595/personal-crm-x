<?php

if (!function_exists('format_image_url')) {
    /**
     * Helper to reliably format image asset URLs pointing to public/uploads directory.
     * Handles nulls, absolute HTTP URLs, 'storage/' prefixes, and relative paths.
     */
    function format_image_url(?string $path, string $default = ''): string {
        if (!$path || trim($path) === '') {
            return $default;
        }

        $trimmed = trim($path);

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $trimmed), '/');

        if (str_starts_with($cleanPath, 'uploads/') || str_starts_with($cleanPath, 'assets/') || str_starts_with($cleanPath, 'images/')) {
            return asset($cleanPath);
        }

        return asset('uploads/' . $cleanPath);
    }
}

if (!function_exists('delete_old_upload_file')) {
    /**
     * Safely deletes an old uploaded file from public/uploads/ to prevent disk clutter.
     */
    function delete_old_upload_file(?string $path): void {
        if (!$path || trim($path) === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', trim($path)), '/');
        
        $uploadsFile = public_path('uploads/' . ltrim(str_replace('uploads/', '', $cleanPath), '/'));
        if (file_exists($uploadsFile) && is_file($uploadsFile)) {
            @unlink($uploadsFile);
        }

        $storageFile = storage_path('app/public/' . $cleanPath);
        if (file_exists($storageFile) && is_file($storageFile)) {
            @unlink($storageFile);
        }
    }
}
