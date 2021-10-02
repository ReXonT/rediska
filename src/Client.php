<?php

namespace Merexo\Rediska;

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
    private static $connect = null;

    /**
     * @var Cache|null
     */
    private static $cache = null;

    /**
     * @var Queue|null
     */
    private static $queue = null;

    /**
     * @var DelayedQueue|null
     */
    private static $delayed_queue = null;

    /**
     * @var Mutex|null
     */
    private static $mutex = null;

    /**
     * @var RateLimiter|null
     */
    private static $rate_limiter = null;

    /**
     * @var PubSub|null
     */
    private static $pub_sub = null;

    /**
     * @var Stream|null
     */
    private static $stream = null;

    /**
     * @return Redis|null
     */
    public static function getInstance()
    {
        return self::connect();
    }

    /**
     * @return Cache|null
     */
    public static function cache()
    {
        $redis = self::getInstance();

        if (self::$cache === null) {
            self::$cache = new Cache($redis);
        }

        return self::$cache;
    }

    /**
     * @return Queue|null
     */
    public static function queue()
    {
        $redis = self::getInstance();

        if (self::$queue === null) {
            self::$queue = new Queue($redis);
        }

        return self::$queue;
    }

    /**
     * @return DelayedQueue|null
     */
    public static function delayedQueue()
    {
        $redis = self::getInstance();

        if (self::$delayed_queue === null) {
            self::$delayed_queue = new DelayedQueue($redis);
        }

        return self::$delayed_queue;
    }

    /**
     * @return Mutex|null
     */
    public static function mutex()
    {
        $redis = self::getInstance();

        if (self::$mutex === null) {
            self::$mutex = new Mutex($redis);
        }

        return self::$mutex;
    }

    /**
     * @return RateLimiter|null
     */
    public static function rateLimiter()
    {
        $redis = self::getInstance();

        if (self::$rate_limiter === null) {
            self::$rate_limiter = new RateLimiter($redis);
        }

        return self::$rate_limiter;
    }

    /**
     * @return PubSub|null
     */
    public static function pubSub()
    {
        $redis = self::getInstance();

        if (self::$pub_sub === null) {
            self::$pub_sub = new PubSub($redis);
        }

        return self::$pub_sub;
    }

    /**
     * @return Stream|null
     */
    public static function stream($stream_key)
    {
        $redis = self::getInstance();

        if (self::$stream === null) {
            self::$stream = new Stream($redis, $stream_key);
        }

        return self::$stream;
    }

    /**
     * @return Redis|null
     */
    private static function connect()
    {
        if (self::$connect === null) {
            $redis = new Redis();
            $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);

            self::$connect = $redis;
        }

        return self::$connect;
    }
}