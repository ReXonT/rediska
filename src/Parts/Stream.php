<?php

namespace Merexo\Rediska\Parts;

class Stream extends RedisPart
{
    private const STREAM_VALUES_KEY = 'payload';

    private $stream_key;

    public function __construct(\Redis $redis, $stream_key)
    {
        parent::__construct($redis);
        $this->stream_key = $stream_key;
    }

    /**
     * @param $payload
     * @param string $key
     * @param int $max_len
     * @return string
     */
    public function add($payload, string $key = '*', int $max_len = 0)
    {
        return $this->redis->xAdd($this->stream_key, $key, [self::STREAM_VALUES_KEY => serialize($payload)], $max_len);
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws \Exception
     */
    public function get($key)
    {
        $value = $this->range($key, $this->getNextKey($key));

        if (isset($value[$key])) return $value[$key];

        return null;
    }

    /**
     * @param $key
     * @return int
     */
    public function drop($key)
    {
        return $this->redis->xDel($this->stream_key, $key);
    }

    /**
     * @return int
     */
    public function flush()
    {
        return $this->redis->del($this->stream_key);
    }

    /**
     * @return int
     */
    public function len()
    {
        return $this->redis->xLen($this->stream_key);
    }

    /**
     * Get all values from stream ASC
     *
     * @return array
     */
    public function getAll()
    {
        return $this->range('-', '+');
    }

    /**
     * Get all values from stream DESC
     *
     * @return array
     */
    public function getAllRev()
    {
        return $this->redis->xRevRange($this->stream_key, '-', '+');
    }

    /**
     * Get values in range from stream ASC
     *
     * @param $start
     * @param $end
     * @param int|null $count
     * @return array
     */
    public function range($start, $end, ?int $count = -1)
    {
        $values = $this->redis->xRange($this->stream_key, $start, $end, $count);

        return $this->decodeValuesFromPayload($values);
    }

    /**
     * Get values in range from stream DESC
     *
     * @param $start
     * @param $end
     * @param int|null $count
     * @return array
     */
    public function rangeRev($start, $end, ?int $count = -1)
    {
        $values = $this->redis->xRevRange($this->stream_key, $start, $end, $count);

        return $this->decodeValuesFromPayload($values);
    }

    /**
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function getNextKey(string $key)
    {
        if (strpos($key, '-') === false) {
            throw new \Exception('Wrong key format. Valid format: microtime-id');
        }

        list($time, $id) = explode('-', $key);

        return sprintf('%d-%d', $time, $id + 1);
    }

    /**
     * @param mixed $values
     * @return array
     */
    private function decodeValuesFromPayload($values)
    {
        if (!is_array($values)) return [];

        foreach ($values as &$value) {
            if (!isset($value[self::STREAM_VALUES_KEY])) continue;

            $value = unserialize($value[self::STREAM_VALUES_KEY]);
        }

        return $values;
    }
}