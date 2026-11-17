<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = ['name', 'title', 'bio', 'photo_path', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_staff', 'staff_id', 'service_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class)->orderBy('weekday')->orderBy('start_time');
    }

    public function timeOffs(): HasMany
    {
        return $this->hasMany(TimeOff::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return public_url($this->photo_path);
    }
}
