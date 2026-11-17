<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'code', 'service_id', 'staff_id', 'customer_name', 'customer_phone', 'customer_email',
        'starts_at', 'ends_at', 'status', 'notes', 'admin_notes', 'locale',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public static function makeCode(): string
    {
        // Tanpa karakter yang mudah tertukar (0/O, 1/I).
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'], true);
    }
}
