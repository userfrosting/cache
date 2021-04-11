<?php

/*
 * UserFrosting Cache (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/cache
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/cache/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Cache\Patch\Redis;

use Illuminate\Cache\CacheManager as UpstreamCacheManager;
use UserFrosting\Cache\Patch\Redis\RedisStore;

/**
 * Permits usage of patched `RedisStore`.
 * See https://github.com/userfrosting/cache/issues/8
 */
class CacheManager extends UpstreamCacheManager
{
    protected function createRedisDriver(array $config)
    {
        $redis = $this->app['redis'];

        $connection = $config['connection'] ?? 'default';

        return $this->repository(new RedisStore($redis, $this->getPrefix($config), $connection));
    }
}
