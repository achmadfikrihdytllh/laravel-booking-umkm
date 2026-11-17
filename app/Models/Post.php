<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'cover_path', 'status', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at')->orderByDesc('id');
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $n = 2;
        while (static::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }

    public function getCoverUrlAttribute(): ?string
    {
        return public_url($this->cover_path);
    }
}
