<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/Cache
 * @license   https://github.com/userfrosting/Cache/blob/master/licenses/UserFrosting.md (MIT License)
*/
namespace UserFrosting\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use UserFrosting\Cache\Driver\TaggableFileStore as TaggableFileDriver;

/**
 * TaggableFileStore Class
 *
 * Setup a cache instance using the custom `tfile` driver
 *
 * @author    Louis Charette
 */
class TaggableFileStore extends ArrayStore
{
    /**
     * Extend the `ArrayStore` contructor to accept the tfile driver $path
     * config and setup the necessary config
     *
     * @param string $path (default: "./")
     * @param string $storeName (default: "default")
     * @param Container|null $app
     * @return void
     */
    public function __construct($path = "./", $storeName = "default", Container $app = null)
    {
        // Run the parent function to build base $app and $config
        parent::__construct($storeName, $app);

        // Files store requires a Filesystem access
        $this->app->singleton('files', function() {
            return new Filesystem();
        });

        // Setup the config for this file store
        $this->config['cache.stores'] = [
            $this->storeName => [
                'driver' => 'tfile',
                'path' => $path
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function instance()
    {
        $config = $this->config;
        $this->app->singleton('config', function() use ($config) {
            return $config;
        });

        $cacheManager = new CacheManager($this->app);

        // Register the `tfile` custom driver
        $cacheManager->extend('tfile', function($app, $config)
        {
            $store = new TaggableFileDriver($app['files'], $config['path'], $config);
            return $this->repository($store);
        });

        return $cacheManager->store($this->storeName);
    }
}