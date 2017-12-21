<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/Cache
 * @license   https://github.com/userfrosting/Cache/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Store;

/**
 * ArrayStore Class
 *
 * Class used to setup a cache instance in a defined namespace
 * Uses the `array` driver by default. This driver is a dummy one that doesn't
 * save anything. This class main purpose is to be a base class to be extended
 * by other store
 *
 * @author    Louis Charette
 */
class ArrayStore
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Container Used to create dummy Illuminate Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * Create the empty Illuminate Container and required config
     *
     * @param string $storeName (default: "default")
     * @param Container|null $app (default: null)
     * @return void
     */
    function __construct($storeName = "default", Container $app = null)
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
     * @return Store Laravel Cache instance
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