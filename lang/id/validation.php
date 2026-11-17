<?php

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'email' => 'Format :attribute tidak valid.',
    'min' => ['string' => ':attribute minimal :min karakter.', 'numeric' => ':attribute minimal :min.'],
    'max' => ['string' => ':attribute maksimal :max karakter.', 'numeric' => ':attribute maksimal :max.', 'file' => 'Ukuran :attribute maksimal :max KB.'],
    'integer' => ':attribute harus berupa angka bulat.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_format' => 'Format :attribute tidak valid.',
    'after_or_equal' => ':attribute tidak boleh sebelum :date.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'in' => ':attribute yang dipilih tidak valid.',
    'image' => ':attribute harus berupa gambar.',
    'url' => 'Format :attribute tidak valid.',
    'regex' => 'Format :attribute tidak valid.',
    'array' => ':attribute tidak valid.',
    'boolean' => ':attribute tidak valid.',
    'attributes' => [
        'name' => 'Nama', 'phone' => 'Nomor WhatsApp', 'email' => 'Email', 'service_id' => 'Layanan',
        'staff_id' => 'Petugas', 'date' => 'Tanggal', 'time' => 'Jam', 'notes' => 'Catatan', 'password' => 'Kata sandi',
        'title' => 'Judul', 'body' => 'Isi', 'excerpt' => 'Ringkasan', 'duration_minutes' => 'Durasi',
        'business_name' => 'Nama bisnis', 'start_date' => 'Tanggal mulai', 'end_date' => 'Tanggal selesai',
        'logo' => 'Logo', 'photo' => 'Foto', 'cover' => 'Sampul', 'brand_color' => 'Warna merek',
    ],
];
