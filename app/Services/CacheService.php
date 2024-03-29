<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CacheService
{
    public function exists(string $key)
    {
        return Redis::exists($key);
    }

    public function set(string $key, $data, int $time = 20)
    {
        Redis::set($key, serialize($data), 'EX', $time);
    }

    public function get(string $key)
    {
        return unserialize(Redis::get($key));
    }
}