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

use Illuminate\Filesystem\Filesystem;

class FileStore extends ArrayStore {

    /**
     * Extend the `ArrayStore` contructor to accept the file driver $path
     * config and setup the necessary config
     *
     * @access public
     * @param string $path (default: "./")
     * @param string $storeName (default: "default")
     * @param mixed $app
     * @return void
     */
    public function __construct($path = "./", $storeName = "default", $app = null)
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
                'driver' => 'file',
                'path' => $path
            ]
        ];
    }
}