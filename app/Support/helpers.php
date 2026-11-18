<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::read($key, $default);
    }
}

if (! function_exists('normalize_phone')) {
    /** "0812-345 678" / "+62 812 345 678" -> "62812345678" (Indonesia sebagai bawaan). */
    function normalize_phone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }
        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }
}

if (! function_exists('wa_link')) {
    function wa_link(?string $phone, ?string $text = null): ?string
    {
        $n = normalize_phone($phone);
        if ($n === '') {
            return null;
        }

        return 'https://wa.me/'.$n.($text ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('public_url')) {
    function public_url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}

if (! function_exists('staff_label')) {
    function staff_label(): string
    {
        $key = app()->getLocale() === 'en' ? 'staff_label_en' : 'staff_label_id';

        return (string) setting($key, 'Staff');
    }
}

if (! function_exists('service_label')) {
    function service_label(): string
    {
        $key = app()->getLocale() === 'en' ? 'service_label_en' : 'service_label_id';

        return (string) setting($key, 'Service');
    }
}

if (! function_exists('brand_color')) {
    function brand_color(): string
    {
        $c = (string) setting('brand_color', '#0d9488');

        return preg_match('/^#[0-9a-fA-F]{6}$/', $c) ? $c : '#0d9488';
    }
}

if (! function_exists('on_brand')) {
    /** Warna teks (putih/hitam) yang terbaca di atas warna merek. */
    function on_brand(): string
    {
        $c = ltrim(brand_color(), '#');
        [$r, $g, $b] = [hexdec(substr($c, 0, 2)), hexdec(substr($c, 2, 2)), hexdec(substr($c, 4, 2))];
        $lum = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $lum > 0.6 ? '#111827' : '#ffffff';
    }
}
