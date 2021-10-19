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
    private $connect = null;

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

    public function __construct($host = 'localhost', $port = 6379)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return Redis|null
     */
    public function getInstance()
    {
        return $this->connect();
    }

    /**
     * @return Cache|null
     */
    public function cache()
    {
        $redis = $this->getInstance();

        if ($this->cache === null) {
            $this->cache = new Cache($redis);
        }

        return $this->cache;
    }

    /**
     * @return Queue|null
     */
    public function queue()
    {
        $redis = $this->getInstance();

        if ($this->queue === null) {
            $this->queue = new Queue($redis);
        }

        return $this->queue;
    }

    /**
     * @return DelayedQueue|null
     */
    public function delayedQueue()
    {
        $redis = $this->getInstance();

        if ($this->delayed_queue === null) {
            $this->delayed_queue = new DelayedQueue($redis);
        }

        return $this->delayed_queue;
    }

    /**
     * @return Mutex|null
     */
    public function mutex()
    {
        $redis = $this->getInstance();

        if ($this->mutex === null) {
            $this->mutex = new Mutex($redis);
        }

        return $this->mutex;
    }

    /**
     * @return RateLimiter|null
     */
    public function rateLimiter()
    {
        $redis = $this->getInstance();

        if ($this->rate_limiter === null) {
            $this->rate_limiter = new RateLimiter($redis);
        }

        return $this->rate_limiter;
    }

    /**
     * @return PubSub|null
     */
    public function pubSub()
    {
        $redis = $this->getInstance();

        if ($this->pub_sub === null) {
            $this->pub_sub = new PubSub($redis);
        }

        return $this->pub_sub;
    }

    /**
     * @return Stream|null
     */
    public function stream($stream_key)
    {
        $redis = $this->getInstance();

        if ($this->stream === null) {
            $this->stream = new Stream($redis, $stream_key);
        }

        return $this->stream;
    }

    /**
     * @return Set|null
     */
    public function set($stream_key)
    {
        $redis = $this->getInstance();

        if ($this->set === null) {
            $this->set = new Set($redis, $stream_key);
        }

        return $this->set;
    }

    /**
     * @return Redis|null
     */
    private function connect()
    {
        if ($this->connect === null) {
            $redis = new Redis();
            $redis->connect($this->host ?: $_ENV['REDIS_HOST'], $this->port ?: $_ENV['REDIS_PORT']);

            $this->connect = $redis;
        }

        return $this->connect;
    }
}