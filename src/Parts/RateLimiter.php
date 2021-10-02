<?php

namespace Merexo\Rediska\Parts;

class RateLimiter extends RedisPart
{
    /**
     * Incr and check limit for key
     *
     * @param string $method
     * @param int $user_id
     * @param int $limit
     * @return bool
     */
    public function isLimitReached(string $method, int $user_id, int $limit): bool
    {
        $current_time = time();
        $time_window = $current_time - ($current_time % 60);
        $key = sprintf('api_%s_%d_%d', $method, $user_id, $time_window);
        $count = $this->redis->incr($key);

        $this->redis->expire($key, 60);

        return $count > $limit;
    }
}