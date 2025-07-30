<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_role_name')) {
    function get_role_name($role)
    {
        return match ($role) {
            'super_admin' => 'Super Admin',
            'referee' => 'Wasit',
            'photographer' => 'Fotografer',
            'field_manager' => 'Pengelola Lapangan',
            'community' => 'Komunitas',
            default => 'Tidak Diketahui',
        };
    }
}

if (!function_exists('authorized')) {
    function authorized(...$roles)
    {
        if (auth()->guest()) {
            return false;
        }

        $user = auth()->user();

        if (count($roles) > 0 && !in_array($user->role, $roles)) {
            return false;
        }

        // Check if the user is a community and has no service set
        if (in_array($user->role, ['referee', 'photographer']) && !$user->service) {
            return false;
        }

        return true;
    }
}

if (!function_exists('format_rp')) {
    function format_rp($number)
    {
        try {
            return 'Rp ' . number_format($number, 0, ',', '.');
        } catch (\Exception $e) {
            return 'Rp 0';
        }
    }
}

if (!function_exists('store_image')) {
    function store_image($image, $path)
    {
        try {
            $imageName = Str::random(8) . '_' . $image->hashName();

            return $image->storeAs($path, $imageName, 'public');
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('get_image_url')) {
    function get_image_url($image, $fallback = null)
    {
        return $image && Storage::disk('public')->exists($image)
            ? Storage::url($image)
            : ($fallback ?? asset('images/default.png'));
    }
}

if (!function_exists('delete_image')) {
    function delete_image($image)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }
    }
}

const PEKANBARU_REGENCY_ID = 1471;
