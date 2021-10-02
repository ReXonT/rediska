<?php

namespace Merexo\Rediska\Parts;

class PubSub extends RedisPart
{
    /**
     * @param $channel
     * @param $message
     * @return int
     */
    public function publishMessage($channel, $message)
    {
        return $this->redis->publish($channel, $message);
    }
}