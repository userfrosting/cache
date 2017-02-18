<?php

/**
 * FileStore Class
 *
 * Setup a cache instance in a defined namespace using the `file` driver
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use UserFrosting\Cache\CacheStore;
use Illuminate\Filesystem\Filesystem;

class FileStore extends ArrayStore {

    /**
     * Extend the `ArrayStore` contructor to accept the file driver $path
     * config and setup the necessary config
     *
     * @access public
     * @param mixed $cacheNamespace
     * @param string $path (default: "./")
     * @return void
     */
    public function __construct($cacheNamespace = "", $path = "./") {

        // Run the parent function to build base $app and $config
        parent::__construct($cacheNamespace);

        // Files store requires a Filesystem access
        $this->app->singleton('files', function() {
            return new Filesystem();
        });

        // Setup the config for this file store
        $this->config['cache.stores'] = [
            $this->cacheNamespace => [
                'driver' => 'file',
                'path' => $path . "/" . $cacheNamespace
            ]
        ];
    }
}