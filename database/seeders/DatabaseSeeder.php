<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin pertama dari .env (jangan taruh kata sandi di kode).
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD');
        if (! $password) {
            $this->command?->warn('ADMIN_PASSWORD belum diisi di .env, admin tidak dibuat.');
        } else {
            User::updateOrCreate(['email' => $email], ['name' => 'Admin', 'password' => $password, 'role' => 'owner']);
        }

        if (Service::exists()) {
            return; // data contoh hanya untuk instalasi kosong
        }

        foreach ([
            'business_name' => 'Klinik Sehat Contoh',
            'tagline' => 'Pelayanan kesehatan keluarga yang ramah dan terpercaya',
            'about' => "Ini adalah data contoh. Ubah nama, logo, dan deskripsi di Admin > Pengaturan.",
            'whatsapp' => '081234567890',
            'address' => 'Jl. Contoh No. 1, Jakarta',
            'opening_hours' => "Senin-Jumat 09.00-17.00\nSabtu 09.00-13.00",
        ] as $k => $v) {
            Setting::write($k, $v);
        }

        $umum = Service::create(['name' => 'Konsultasi Umum', 'description' => 'Pemeriksaan dan konsultasi kesehatan.', 'duration_minutes' => 30, 'price_label' => 'Mulai Rp 100.000', 'sort_order' => 1]);
        $gigi = Service::create(['name' => 'Pemeriksaan Gigi', 'description' => 'Pemeriksaan dan pembersihan gigi.', 'duration_minutes' => 45, 'sort_order' => 2]);

        $a = Staff::create(['name' => 'dr. Andi Pratama', 'title' => 'Dokter Umum', 'sort_order' => 1]);
        $b = Staff::create(['name' => 'drg. Sinta Dewi', 'title' => 'Dokter Gigi', 'sort_order' => 2]);
        $a->services()->sync([$umum->id]);
        $b->services()->sync([$gigi->id, $umum->id]);

        foreach (range(1, 5) as $d) {
            $a->schedules()->create(['weekday' => $d, 'start_time' => '09:00', 'end_time' => '12:00']);
            $a->schedules()->create(['weekday' => $d, 'start_time' => '13:00', 'end_time' => '16:00']);
        }
        foreach ([2, 4, 6] as $d) {
            $b->schedules()->create(['weekday' => $d, 'start_time' => '10:00', 'end_time' => '15:00']);
        }

        Post::create(['title' => 'Selamat datang', 'slug' => 'selamat-datang', 'excerpt' => 'Reservasi kini bisa dilakukan online.',
            'body' => "Sekarang Anda dapat membuat reservasi tanpa antre dan tanpa perlu membuat akun.", 'status' => 'published', 'published_at' => now()]);
    }
}
