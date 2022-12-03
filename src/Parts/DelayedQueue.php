<?php

namespace Merexo\Rediska\Parts;

class DelayedQueue extends RedisPart
{
    public function push(string $queue_name, $payload, int $delay = 0)
    {
        return $this->redis->rawCommand('ZADD', $queue_name, 'NX', time() + $delay, serialize($payload));
    }

    public function pop(string $queue_name)
    {
        $command = '
                local val = redis.call(\'ZRANGEBYSCORE\', KEYS[1], 0, ARGV[1], \'LIMIT\', 0, 1)[1]
                if val then
                    redis.call(\'ZREM\', KEYS[1], val)
                end
                return val
        ';

        return $this->redis->eval($command, [$queue_name, time()], 1);
    }

    public function len(string $queue_name)
    {
        return $this->redis->zCard($queue_name);
    }
}