rediska
==========

Test composer package for Redis.

## Installation

Installation via [Composer](https://getcomposer.org/):
```bash
composer require merexo/rediska
```

## Host/Port

Default values: host = 'localhost', port = 6379. If you have .env, you can use arguments as ```null, null``` for host and port. In this case, Client will take ```$_ENV['REDIS_HOST']``` for host and ```$_ENV['REDIS_PORT']``` for port.

Example:
```php
$client = new \Merexo\Rediska\Client(null, null); // try to take params from .env 
```

## Default Use

```php
$client = new \Merexo\Rediska\Client;
$client->cache()->set('key', 'my_value');

echo $client->cache()->get('key');

...

$stream = $client->stream('stream_key');
$key = $stream->add(['key' => 'value', 'object' => new \StdClass]);

print_r($stream->get($key)); // response: ['key' => 'value', 'object' => new \StdClass]

$stream->flush();
```