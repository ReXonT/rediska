<?php

namespace Merexo\Rediska\Parts;

class Cache extends RedisPart
{
    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     */
    public function set(string $key, $value, int $ttl = 3600)
    {
        $this->redis->set($key, $value, ['EX' => $ttl]);
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
     */
    public function drop(string $key)
    {
        $this->redis->del($key);
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
     */
    public function dropValues(array $keys)
    {
        $this->redis->rawCommand('MDEL', ...$keys);
    }
}