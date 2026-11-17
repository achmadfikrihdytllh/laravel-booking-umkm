<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'duration_minutes', 'price_label', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'duration_minutes' => 'integer'];
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'service_staff', 'service_id', 'staff_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
