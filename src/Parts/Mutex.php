<?php

namespace Merexo\Rediska\Parts;

class Mutex extends RedisPart
{
    public function lock(string $key, string $hash, int $ttl = 10): bool
    {
        return (bool)$this->redis->rawCommand('SET', $key, $hash, 'NX', 'EX', $ttl);
    }

    public function tryLock(string $key, string $hash, int $timeout, int $ttl = 10): bool
    {
        $start_time = microtime(true);
        while (!$this->lock($key, $hash, $ttl)) {
            if ((microtime(true) - $start_time) > $timeout) {
                return false; // не удалось взять shared ресурс под блокировку за указанный $timeout
            }
            usleep(500 * 1000); //ждем 500 миллисекунд до следующей попытки поставить блокировку
        }

        return true; //блокировка успешно поставлена
    }

    public function releaseLock(string $key, string $hash): bool
    {
        $command = '
                if redis.call("GET",KEYS[1])==ARGV[1] then
                    return redis.call("DEL",KEYS[1])
                else
                    return 0
                end
        ';
        return (bool)$this->redis->eval($command, [$key, $hash], 1);
    }
}