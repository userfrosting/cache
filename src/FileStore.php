<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/Cache
 * @license   https://github.com/userfrosting/Cache/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/**
 * FileStore Class
 *
 * Setup a cache instance in a defined namespace using the `file` driver
 *
 * @author    Louis Charette
 */
class FileStore extends ArrayStore
{
    /**
     * Extend the `ArrayStore` contructor to accept the file driver $path
     * config and setup the necessary config
     *
     * @param string $path (default: "./")
     * @param string $storeName (default: "default")
     * @param Container|null $app (default: null)
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
                'driver' => 'file',
                'path' => $path
            ]
        ];
    }
}