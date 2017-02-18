<?php

/**
 * MemcachedStore Class
 *
 * Setup a cache instance in a defined namespace using the `memcached` driver
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use UserFrosting\Cache\CacheStore;
use Illuminate\Cache\MemcachedConnector;

class MemcachedStore extends ArrayStore {

    /**
     * Extend the `ArrayStore` contructor to accept the memcached server and
     * port configuraton
     *
     * @access public
     * @param mixed $cacheNamespace
     * @param string $server (default: "127.0.0.1", the default memcached ip)
     * @param string $port (default: "11211", the default memcached port)
     * @param mixed $app
     * @return void
     */
    public function __construct($cacheNamespace, $server = "127.0.0.1", $port = "11211", $app = null)
    {

        // Run the parent function to build base $app and $config
        parent::__construct($cacheNamespace, $app);

        // Memcached store requires a MemcachedConnector
        $this->app->singleton('memcached.connector', function() {
            return new MemcachedConnector;
        });

        // Setup the config for this file store
        $this->config['cache'] = [
            'prefix' => $this->cacheNamespace,
            'stores' => [
                $this->storeName => [
                    'driver' => 'memcached',
                    'servers' => [
                        [
                            'host' => $server,
                            'port' => $port,
                            'weight' => 100
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * To make use of the namespace, the memcached driver uses the tags feature
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