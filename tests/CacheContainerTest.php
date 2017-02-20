<?php

namespace UserFrosting\Cache;

use PHPUnit\Framework\TestCase;

class CacheContainerTest extends TestCase
{
    /**
     * Test basic array store
     */
    public function testArrayStore()
    {
        // Create the $cache object
        $cacheStore = new ArrayStore("global");
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "array");
        $this->assertEquals("array", $cache->get('foo'));
    }

    public function testArrayStorePersistence()
    {
        // Create the $cache object
        $cacheStore = new ArrayStore("global");
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        // Won't work, because array doesn't save anything
        $this->assertNotEquals("array", $cache->get('foo'));
    }

    /**
     * Test file store
     */
    public function testFileStore()
    {
        // Create the $cache object
        $cacheStore = new FileStore("global", "./tests/cache");
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "bar");
        $this->assertEquals("bar", $cache->get('foo'));
    }

    public function testFileStorePersistence()
    {
        // Create the $cache object
        $cacheStore = new FileStore("global", "./tests/cache");
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("bar", $cache->get('foo'));
    }

    public function testMultipleFileStore()
    {
        // Create two $cache object
        $cacheStore = new FileStore("global", "./tests/cache");
        $cacheGlobal = $cacheStore->instance();

        $cacheStore2 = new FileStore("user2419", "./tests/cache");
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

    /**
     * Test redis store
     */
    public function testRedisStore()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new RedisStore("global");
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "Redis bar");
        $this->assertEquals("Redis bar", $cache->get('foo'));
    }

    public function testRedisStorePersistence()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new RedisStore("global");
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("Redis bar", $cache->get('foo'));
    }

    public function testMultipleRedisStore()
    {
        // Create two $cache object
        $cacheStore = new RedisStore("global");
        $cacheGlobal = $cacheStore->instance();

        $cacheStore2 = new RedisStore("user2419");
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

    /**
     * Misc Tests
     */
    public function testNoNamespace()
    {
        $cacheStore = new ArrayStore();
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "testNoNamespace");
        $this->assertEquals("testNoNamespace", $cache->get('foo'));
    }

    public function testReuseApp()
    {
        $app = new \Illuminate\Container\Container();

        // Create two $cache object
        $cacheStore = new FileStore("global", "./tests/cache", $app);
        $cacheGlobal = $cacheStore->instance();

        $cacheStore2 = new FileStore("user2419", "./tests/cache", $app);
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