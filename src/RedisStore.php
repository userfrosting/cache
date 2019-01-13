<?php
/**
 * UserFrosting Cache (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/cache
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/cache/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Cache;

use Illuminate\Container\Container;
use Illuminate\Redis\RedisManager;

/**
 * RedisStore Class
 *
 * Setup a cache instance in a defined namespace using the `redis` driver
 *
 * @author    Louis Charette
 */
class RedisStore extends ArrayStore
{
    /**
     * Extend the `ArrayStore` contructor to accept the redis server and
     * port configuraton
     *
     * @param mixed          $redisServer (default: [])
     * @param string         $storeName   (default: "default")
     * @param Container|null $app
     */
    public function __construct($redisServer = [], $storeName = 'default', Container $app = null)
    {

        // Run the parent function to build base $app and $config
        parent::__construct($storeName, $app);

        // Setup Redis server config
        $redisConfig = [
            'default' => array_merge([
                'host'     => '127.0.0.1',
                'password' => null,
                'port'     => 6379,
                'database' => 0,
                'prefix'   => ''
            ], $redisServer)
        ];

        // Setup the config for this file store
        $this->config['cache'] = [
            'prefix' => $redisConfig['default']['prefix'],
            'stores' => [
                $this->storeName => [
                    'driver'     => 'redis',
                    'connection' => 'default'
                ]
            ]
        ];

        // Register redis manager
        $this->app->singleton('redis', function ($app) use ($redisConfig) {
            try {
                return new RedisManager('predis', $redisConfig);
            } catch (\ArgumentCountError $e) {
                return new RedisManager($app, 'predis', $redisConfig);
            }
        });
    }
}
