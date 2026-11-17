<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    private const CACHE = 'settings.all';

    /** @return array<string,mixed> */
    public static function all_(): array
    {
        return Cache::rememberForever(self::CACHE, fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function read(string $key, mixed $default = null): mixed
    {
        $all = static::all_();
        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return $all[$key];
        }

        return config("booking.defaults.$key", $default);
    }

    public static function write(string $key, mixed $value): void
    {
        static::query()->updateOrInsert(['key' => $key], ['value' => $value === null ? null : (string) $value]);
        Cache::forget(self::CACHE);
    }
}
