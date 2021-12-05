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

    /**
     * @var Cache|null
     */
    private $cache = null;

    /**
     * @var Queue|null
     */
    private $queue = null;

    /**
     * @var DelayedQueue|null
     */
    private $delayed_queue = null;

    /**
     * @var Mutex|null
     */
    private $mutex = null;

    /**
     * @var RateLimiter|null
     */
    private $rate_limiter = null;

    /**
     * @var PubSub|null
     */
    private $pub_sub = null;

    /**
     * @var Stream|null
     */
    private $stream = null;

    /**
     * @var Set|null
     */
    private $set = null;

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
        if ($this->cache === null) {
            $this->cache = new Cache($this->redis);
        }

        return $this->cache;
    }

    /**
     * @return Queue|null
     */
    public function queue()
    {
        if ($this->queue === null) {
            $this->queue = new Queue($this->redis);
        }

        return $this->queue;
    }

    /**
     * @return DelayedQueue|null
     */
    public function delayedQueue()
    {
        if ($this->delayed_queue === null) {
            $this->delayed_queue = new DelayedQueue($this->redis);
        }

        return $this->delayed_queue;
    }

    /**
     * @return Mutex|null
     */
    public function mutex()
    {
        if ($this->mutex === null) {
            $this->mutex = new Mutex($this->redis);
        }

        return $this->mutex;
    }

    /**
     * @return RateLimiter|null
     */
    public function rateLimiter()
    {
        if ($this->rate_limiter === null) {
            $this->rate_limiter = new RateLimiter($this->redis);
        }

        return $this->rate_limiter;
    }

    /**
     * @return PubSub|null
     */
    public function pubSub()
    {
        if ($this->pub_sub === null) {
            $this->pub_sub = new PubSub($this->redis);
        }

        return $this->pub_sub;
    }

    /**
     * @return Stream|null
     */
    public function stream($stream_key)
    {
        if ($this->stream === null) {
            $this->stream = new Stream($this->redis, $stream_key);
        }

        return $this->stream;
    }

    /**
     * @return Set|null
     */
    public function set($stream_key)
    {
        if ($this->set === null) {
            $this->set = new Set($this->redis, $stream_key);
        }

        return $this->set;
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