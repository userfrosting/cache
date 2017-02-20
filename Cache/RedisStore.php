<?php

/**
 * RedisStore Class
 *
 * Setup a cache instance in a defined namespace using the `redis` driver
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use UserFrosting\Cache\CacheStore;
use Illuminate\Redis\RedisManager;

class RedisStore extends ArrayStore {

    /**
     * Extend the `ArrayStore` contructor to accept the redis server and
     * port configuraton
     *
     * @access public
     * @param mixed $cacheNamespace
     * @param mixed $redisServer (default: [])
     * @param mixed $app (default: null)
     * @return void
     */
    public function __construct($cacheNamespace, $redisServer = [], $app = null)
    {

        // Run the parent function to build base $app and $config
        parent::__construct($cacheNamespace, $app);

        // Setup the config for this file store
        $this->config['cache'] = [
            'prefix' => $this->cacheNamespace,
            'stores' => [
                $this->storeName => [
                    'driver' => 'redis',
                    'connection' => 'default'
                ]
            ]
        ];

        // Setup Redis server config
        $redisConfig = [
            'default' => array_merge([
                'host' => '127.0.0.1',
                'password' => null,
                'port' => 6379,
                'database' => 0
            ], $redisServer)
        ];

        // Register redis manager
        $this->app->singleton('redis', function ($app) use ($redisConfig) {
            return new RedisManager('predis', $redisConfig);
        });
    }

    /**
     * To make use of the namespace, the redis driver uses the tags feature
     * to tag every key with the namespace
     *
     * @access public
     * @return Laravel Cache instance
     */
    public function instance()
    {
        $instance = parent::instance();
        return $instance->tags($this->cacheNamespace);
    }
}