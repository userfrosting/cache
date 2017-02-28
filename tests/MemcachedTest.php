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
        $cacheStore = new MemcachedStore();
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "memcached bar");
        $this->assertEquals("memcached bar", $cache->get('foo'));
    }

    public function testMemcachedStorePersistence()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new MemcachedStore();
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("memcached bar", $cache->get('foo'));
    }

    public function testMultipleMemcachedStore()
    {
        // Create two $cache object
        $cacheStore = new MemcachedStore();
        $cache = $cacheStore->instance();

        // Store stuff in first
        $cache->tags('global')->forever("test", "1234");
        $cache->tags('global')->forever("foo", "bar");
        $cache->tags('global')->forever("cities", ['Montréal', 'Paris', 'NYC']);

        // Store stuff in second
        $cache->tags('user')->forever("test", "1234");
        $cache->tags('user')->forever("foo", "BARRRRRRRRE");
        $cache->tags('user')->forever("cities", ['Montréal', 'Paris', 'NYC']);

        // Flush first
        $cache->tags('global')->flush();

        // First show be empty, but not the second one
        $this->assertEquals(null, $cache->tags('global')->get('foo'));
        $this->assertEquals("BARRRRRRRRE", $cache->tags('user')->get('foo'));
    }
}