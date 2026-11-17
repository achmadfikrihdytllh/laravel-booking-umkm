<?php

// Nilai bawaan pengaturan bisnis. Pemilik mengubahnya lewat Admin > Pengaturan (disimpan di tabel settings).
return [
    'defaults' => [
        'business_name' => env('APP_NAME', 'Reservasi'),
        'tagline' => 'Reservasi online, mudah dan cepat',
        'about' => '',
        'logo_path' => null,
        'brand_color' => '#0f766e',
        'whatsapp' => '',            // nomor WhatsApp bisnis, contoh 0812xxxx atau 62812xxxx
        'wa_greeting' => 'Halo, saya ingin bertanya.',
        'email' => '',
        'phone' => '',
        'address' => '',
        'maps_url' => '',
        'instagram' => '',
        'opening_hours' => '',
        'staff_label_id' => 'Dokter',
        'staff_label_en' => 'Doctor',
        'service_label_id' => 'Layanan',
        'service_label_en' => 'Service',
        'slot_step' => 30,           // menit
        'min_notice_hours' => 2,
        'max_advance_days' => 60,
        'auto_confirm' => 0,         // 1 = reservasi langsung "dikonfirmasi"
    ],
    'statuses' => ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'],
    'blocking_statuses' => ['pending', 'confirmed', 'completed'],
];
