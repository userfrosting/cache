<?php

/*
 * UserFrosting Cache (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/cache
 * @copyright Copyright (c) 2013-2021 Alexander Weissman
 * @license   https://github.com/userfrosting/cache/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Cache\Tests;

use Illuminate\Contracts\Cache\Repository as CacheRepositoryContract;
use PHPUnit\Framework\TestCase;

abstract class StoreTestCase extends TestCase
{
    /**
     * @return CacheRepositoryContract Laravel Cache instance
     */
    abstract protected function createStore();

    /**
     * Ensure consistent behaviors across all cache providers.
     */
    public function testPutValueHandling()
    {
        $cache = $this->createStore();

        // string
        $cache->put('string', 'foobar');
        $this->assertSame('foobar', $cache->get('string'));

        // int
        $cache->put('string', 999);
        $this->assertSame(999, $cache->get('string'));

        // array filled
        $cache->put('array_filled', [ 'a', 'b', 'c', 1, 2, 3 ]);
        $this->assertSame(null, $cache->get('array'));
        
        // array empty
        $cache->put('array_empty', []);
        $this->assertSame(null, $cache->get('array'));
        
        // object
        $cache->put('object', (object)[]);
        $this->assertSame(null, $cache->get('array'));
    }
}
