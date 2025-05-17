<?php

namespace App\Services\Interfaces;

interface CacheServiceInterface
{
    public function remember(
        string $key,
        callable $callback,
        ?int $ttl = null
    ): mixed;
    public function rememberWithTags(
        array $tags,
        string $key,
        callable $callback,
        ?int $ttl = null): mixed;
    public function has(string $key): bool;
    public function forget(string $key): bool;
    public function flushTags(array $tags): bool;
    public function flush(): bool;
}
