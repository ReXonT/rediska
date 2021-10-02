<?php

namespace Merexo\Rediska\Parts;

class Queue extends RedisPart
{
    /**
     * @param string $queue_name
     * @param $payload
     * @return false|int
     */
    public function push(string $queue_name, $payload)
    {
        return $this->redis->rPush($queue_name, serialize($payload));
    }

    /**
     * @param string $queue_name
     * @return bool|mixed
     */
    public function pop(string $queue_name)
    {
        return $this->redis->lPop($queue_name);
    }

    /**
     * @param string $queue_name
     * @return bool|int
     */
    public function len(string $queue_name)
    {
        return $this->redis->lLen($queue_name);
    }
}