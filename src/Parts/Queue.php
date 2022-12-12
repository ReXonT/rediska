<?php

namespace Merexo\Rediska\Parts;

class Queue extends RedisPart
{
    /**
     * @return false|int
     * @throws \RedisException
     */
    public function push(string $queue_name, $payload)
    {
        return $this->redis->rPush($queue_name, serialize($payload));
    }

    /**
     * @return bool|mixed
     * @throws \RedisException
     */
    public function pop(string $queue_name, $block = false, $timeout = 10)
    {
        if (!$block) {
            return $this->redis->lPop($queue_name);
        } else {
            return $this->redis->blPop($queue_name, $timeout);
        }
    }

    /**
     * @return bool|int
     * @throws \RedisException
     */
    public function len(string $queue_name)
    {
        return $this->redis->lLen($queue_name);
    }
}