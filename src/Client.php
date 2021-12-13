<?php

namespace Merexo\Rediska;

use Merexo\Rediska\Parts\Set;
use Redis;
use Merexo\Rediska\Parts\Cache;
use Merexo\Rediska\Parts\DelayedQueue;
use Merexo\Rediska\Parts\Mutex;
use Merexo\Rediska\Parts\PubSub;
use Merexo\Rediska\Parts\Queue;
use Merexo\Rediska\Parts\RateLimiter;
use Merexo\Rediska\Parts\Stream;

class Client
{
    /**
     * @var Redis|null
     */
    private $redis = null;

    private $host;
    private $port;

    public function __construct($host = 'localhost', $port = 6379, $permanent = false)
    {
        $this->host = $host ?? $_ENV['REDIS_HOST'];
        $this->port = $port ?? $_ENV['REDIS_PORT'];

        $this->connect($permanent);
    }

    /**
     * @return Redis|null
     */
    public function getInstance($permanent = false)
    {
        return $this->connect($permanent);
    }

    /**
     * @return Cache|null
     */
    public function cache()
    {
        return new Cache($this->redis);
    }

    /**
     * @return Queue|null
     */
    public function queue()
    {
        return new Queue($this->redis);
    }

    /**
     * @return DelayedQueue|null
     */
    public function delayedQueue()
    {
        return new DelayedQueue($this->redis);
    }

    /**
     * @return Mutex|null
     */
    public function mutex()
    {
        return new Mutex($this->redis);
    }

    /**
     * @return RateLimiter|null
     */
    public function rateLimiter()
    {
        return new RateLimiter($this->redis);
    }

    /**
     * @return PubSub|null
     */
    public function pubSub()
    {
        return new PubSub($this->redis);
    }

    /**
     * @return Stream|null
     */
    public function stream($stream_key)
    {
        return new Stream($this->redis, $stream_key);
    }

    /**
     * @return Set|null
     */
    public function set($stream_key)
    {
        return new Set($this->redis, $stream_key);
    }

    /**
     * @return Redis|null
     */
    private function connect($permanent = false)
    {
        if ($this->redis === null) {
            $redis = new Redis();

            if ($permanent) {
                $redis->pconnect($this->host, $this->port);
            } else {
                $redis->connect($this->host, $this->port);
            }

            $this->redis = $redis;
        }

        return $this->redis;
    }
}