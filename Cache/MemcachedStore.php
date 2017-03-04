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

use Illuminate\Cache\MemcachedConnector;

class MemcachedStore extends ArrayStore {

    /**
     * Extend the `ArrayStore` contructor to accept the memcached server and
     * port configuraton
     *
     * @access public
     * @param array $memcachedConfig (default: [])
     * @param string $storeName (default: "default")
     * @param mixed $app
     * @return void
     */
    public function __construct($memcachedConfig = [], $storeName = "default", $app = null)
    {

        // Run the parent function to build base $app and $config
        parent::__construct($storeName, $app);

        // Merge argument config with default one
        $memcachedConfig = array_merge([
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
            'prefix' => ''
        ], $memcachedConfig);

        // Memcached store requires a MemcachedConnector
        $this->app->singleton('memcached.connector', function() {
            return new MemcachedConnector;
        });

        // Setup the config for this file store
        // Nb.: Yes. The `servers` part is in a double array.
        $this->config['cache'] = [
            'prefix' => $memcachedConfig['prefix'],
            'stores' => [
                $this->storeName => [
                    'driver' => 'memcached',
                    'servers' => [
                        $memcachedConfig
                    ]
                ]
            ]
        ];
    }
}