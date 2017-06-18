# Cache module for UserFrosting 4.1

Louis Charette, 2017

Wrapper function for *Laravel cache system* for easier integration of the cache system in standalone projects. Refer to [Laravel documentation](https://laravel.com/docs/5.4/cache) for all cache related function. This wrapper support Laravel `ArrayStore`, `FileStore`, `MemcachedStore` and `RedisStore`.

Also include a `namespace` parameter to handle multiple cache instance on the same server or project.

## Usage

For any store driver, you first need to create a new `*Store`, passing the config values for each of those stores. Once a new store instantiated, you need to use the `instance` function to get the *cache instance*.

To use one of the following stores, you first need to include add it to the `use` list of your class or php file.

If you need to use multiple cache instance on the same server (especially for Memcached and Redis drivers) or on the same project (for example, user based cache), you can use the `namespace` parameter in the `*Store` constructor. A namespace should always be a *string*.

Since he *Laravel cache* requires an instance of `Illuminate\Container\Container`, every `*Store` in this package will create
one automatically. If you want to reuse an existing `Container`, you can pass it to the `*Store` constructor.

While the `namespace` parameter is required for any `*Store`, all other parameters are optional, unless otherwise specified.

### ArrayStore
The [ArrayStore](https://laravel.com/api/5.4/Illuminate/Cache/ArrayStore.html) is a dummy store that doesn't really save anything. This can be used if you want to disable cache globally.

The `ArrayStore` accepts the `namespace` and `Container` parameters: `new ArrayStore(string $namespace, Illuminate\Container\Container $app = null);`

*Example :*
```
use UserFrosting\Cache\ArrayStore;

...

$cacheStore = new ArrayStore("global");
$cache = $cacheStore->instance();

$cache->get(...);
```

### FileStore

The [FileStore](https://laravel.com/api/5.4/Illuminate/Cache/FileStore.html) save file to the filesystem.

The `FileStore` accepts the `namespace`, `path` and `Container` parameters : `new FileStore(string $namespace, string $path = "./", Illuminate\Container\Container $app = null);`

*Example :*
```
use UserFrosting\Cache\FileStore;

...

$cacheStore = new FileStore("global", "./cache");
$cache = $cacheStore->instance();

$cache->get(...);
```

### MemcachedStore

The [MemcachedStore](https://laravel.com/api/5.4/Illuminate/Cache/MemcachedStore.html) uses [Memcached](http://www.memcached.org) to efficiently handle caching.

> **Memcached** should't be mistaken for the **Memcache**. Those are tow separate things !

The `MemcachedStore` accepts the `namespace`, `config` and `Container` parameters : `new MemcachedStore(string $namespace, array $config = [], Illuminate\Container\Container $app = null);`

Memcached config's array contain the settings for your Memcached instance. Default values are:
```
[
  'host' => '127.0.0.1',
  'port' => 11211,
  'weight' => 100
]
```

Custom config can be overwritten using this parameter.

*Example :*
```
use UserFrosting\Cache\MemcachedStore;

...

$cacheStore = new MemcachedStore("global"); //Uses default Memcached settings
$cache = $cacheStore->instance();

$cache->get(...);
```

*Example with custom server and port :*
```
use UserFrosting\Cache\MemcachedStore;

...

$cacheStore = new MemcachedStore("global", [
  'host' => '123.456.789.0',
  'port' => '22122'
]);
$cache = $cacheStore->instance();

$cache->get(...);
```

### RedisStore

The [RedisStore](https://laravel.com/api/5.4/Illuminate/Cache/RedisStore.html) uses [Redis server](https://redis.io) to efficiently handle caching.

The `RedisStore` accepts the `namespace`, `config` and `Container` parameters : `new RedisStore(string $namespace, array $config = [], Illuminate\Container\Container $app = null);`

Redis config's array contain the settings for your Redis server. Default values are:
```
[
  'host' => '127.0.0.1',
  'password' => null,
  'port' => 6379,
  'database' => 0
]
```

Custom config can be overwritten using this parameter.

*Example :*
```
use UserFrosting\Cache\RedisStore;

...

$cacheStore = new RedisStore("global"); //Uses default Redis settings
$cache = $cacheStore->instance();

$cache->get(...);
```

*Example with custom port and password :*
```
use UserFrosting\Cache\RedisStore;

...

$cacheStore = new RedisStore("global", [
  'password' => 'MyAwesomePassword',
  'port' => '1234'
]);
$cache = $cacheStore->instance();

$cache->get(...);
```

## Testing

Before running unit testing, first make sure composer is up to the date (`composer update`). To successfully run all tests, you should also have Memcached and Redis already installed and working. Those tests use the default server and port for each driver. If your environment uses non-default server and port, you might need to edits the test files.

From the base directory, use the following command to run **all** tests:
```
vendor/bin/phpunit  tests
```

Driver specific tests and also be run :
```
# Basic and FileStore related tests
vendor/bin/phpunit  tests/CacheTest

# Memcached related tests
vendor/bin/phpunit  tests/MemcachedTest

# Redis related tests
vendor/bin/phpunit  tests/RedisTest
```
