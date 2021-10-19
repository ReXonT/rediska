<?php

namespace Merexo\Rediska\Parts;

class Cache extends RedisPart
{
    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 3600)
    {
        return $this->redis->set($key, $value, ['EX' => $ttl]);
    }
    
    /**
     * @param string $key
     * @return false|mixed|string
     */
    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param string $key
     * @return int
     */
    public function drop(string $key)
    {
        return $this->redis->del($key);
    }

    /**
     * @param array $keys
     * @return array
     */
    public function getValues(array $keys)
    {
        return $this->redis->mget($keys);
    }

    /**
     * @param array $keys
     * @return mixed
     */
    public function dropValues(array $keys)
    {
        return $this->redis->rawCommand('MDEL', ...$keys);
    }
}