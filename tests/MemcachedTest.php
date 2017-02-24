<?php

namespace UserFrosting\Cache;

use PHPUnit\Framework\TestCase;

class MemcachedTest extends TestCase
{

    /**
     * Test memcached store
     */
    public function testMemcachedStore()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new MemcachedStore("global");
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "memcached bar");
        $this->assertEquals("memcached bar", $cache->get('foo'));
    }

    public function testMemcachedStorePersistence()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new MemcachedStore("global");
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("memcached bar", $cache->get('foo'));
    }

    public function testMultipleMemcachedStore()
    {
        // Create two $cache object
        $cacheStore = new MemcachedStore("global");
        $cacheGlobal = $cacheStore->instance();

        $cacheStore2 = new MemcachedStore("user2419");
        $cacheUser = $cacheStore2->instance();

        // Store stuff in first
        $cacheGlobal->forever("test", "1234");
        $cacheGlobal->forever("foo", "bar");
        $cacheGlobal->forever("cities", ['Montréal', 'Paris', 'NYC']);

        // Store stuff in second
        $cacheUser->forever("test", "1234");
        $cacheUser->forever("foo", "BARRRRRRRRE");
        $cacheUser->forever("cities", ['Montréal', 'Paris', 'NYC']);

        // Flush first
        $cacheGlobal->flush();

        // First show be empty, but not the second one
        $this->assertEquals(null, $cacheGlobal->get('foo'));
        $this->assertEquals("BARRRRRRRRE", $cacheUser->get('foo'));
    }
}