<?php

namespace App\Services;

use App\Services\Interfaces\CacheServiceInterface;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    private int $defaultTTL = 60 * 60; //1h

    private string $prefix = 'app_';


    public function __construct(?int $defaultTTL = null, ?string $prefix = null)
    {
        if ($defaultTTL !== null) {
            $this->defaultTTL = $defaultTTL;
        }

        if ($prefix !== null) {
            $this->prefix = $prefix;
        }
    }

    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        $ttl = $ttl ?? $this->defaultTTL;
        $key = $this->prefix . $key;

        return Cache::remember($key, $ttl, $callback);
    }

    public function rememberWithTags(array $tags, string $key, callable $callback, ?int $ttl = null): mixed
    {
        $ttl = $ttl ?? $this->defaultTTL;
        $key = $this->prefix . $key;

        return Cache::tags($tags)->remember($key, $ttl, $callback);
    }

    public function has(string $key): bool
    {
        return Cache::has($this->prefix . $key);
    }

    public function forget(string $key): bool
    {
        return Cache::forget($this->prefix . $key);
    }

    public function flushTags(array $tags): bool
    {
        return Cache::tags($tags)->flush();
    }

    public function flush(): bool
    {
        return Cache::flush();
    }
}
