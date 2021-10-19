<?php

namespace Merexo\Rediska\Parts;

class Set extends RedisPart
{
    private $set_key;

    public function __construct(\Redis $redis, $set_key)
    {
        parent::__construct($redis);
        $this->set_key = $set_key;
    }

    /**
     * @param $value
     */
    public function add(...$value)
    {
        $this->redis->sAdd($this->set_key, ...$value);
    }

    /**
     * @param $value
     */
    public function remove(...$value)
    {
        $this->redis->sRem($this->set_key, ...$value);
    }
    
    /**
     * @return array
     */
    public function getAll()
    {
        return $this->redis->sMembers($this->set_key);
    }

    /**
     * @return array|bool|mixed|string
     */
    public function pop()
    {
        return $this->redis->sPop($this->set_key);
    }

    /**
     * @return int
     */
    public function len()
    {
        return $this->redis->sCard($this->set_key);
    }

    /**
     * @param $value
     * @return bool
     */
    public function isMember($value)
    {
        return $this->redis->sIsMember($this->set_key, $value);
    }

    /**
     * @return int
     */
    public function drop()
    {
        return $this->redis->del($this->set_key);
    }
}