<?php

namespace Merexo\Rediska\Parts;

class RateLimiter extends RedisPart
{
    /**
     * Incr and check limit for key
     *
     * @param string $key
     * @param int $limit
     * @param int $per_seconds
     * @return bool
     */
    public function isLimitReached(string $key, int $limit, int $per_seconds = 60): bool
    {
        $current_time = time();
        $time_window = $current_time - ($current_time % $per_seconds);
        $key = sprintf('api_%s_%d', $key, $time_window);
        $count = $this->redis->incr($key);

        $this->redis->expire($key, $per_seconds);

        return $count > $limit;
    }
}