<?php

namespace Merexo\Rediska\Parts;

abstract class RedisPart
{
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }
}