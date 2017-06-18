<?php

/**
 * ArrayStore Class
 *
 * Class used to setup a cache instance in a defined namespace
 * Uses the `array` driver by default. This driver is a dummy one that doesn't
 * save anything. This class main purpose is to be a base class to be extended
 * by other store
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use Illuminate\Config\Repository;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;

class ArrayStore {

    /**
     * @var mixed
     */
    protected $config;

    /**
     * @var app
     * Used to create dummy Illuminate Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * Create the empty Illuminate Container and required config
     *
     * @access public
     * @param string $storeName (default: "default")
     * @param mixed $app
     * @return void
     */
    function __construct($storeName = "default", $app = null)
    {
        $this->storeName = $storeName;
        $this->config = new Repository();

        //Throw InvalidArgumentException if namespace argument is not valid
        if (!is_string($this->storeName) || $this->storeName == "")
        {
            throw new \InvalidArgumentException("Store name is not a valid string");
        }

        // Resuse the ctor $app is it exist
        $this->app = ($app instanceof Container) ? $app : new Container();

        // Setup an array store
        $this->config['cache.stores'] = [
            $this->storeName => [
                'driver' => 'array'
            ]
        ];
    }

    /**
     * Return the store instance from the Laravel CacheManager
     *
     * @access public
     * @return Laravel Cache instance
     */
    public function instance()
    {
        $config = $this->config;
        $this->app->singleton('config', function() use ($config) {
            return $config;
        });

        $cacheManager = new CacheManager($this->app);
        return $cacheManager->store($this->storeName);
    }
}