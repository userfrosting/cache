<?php

/**
 * RedisTests
 *
 * Tests for `RedisStore`
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Cache;

use PHPUnit\Framework\TestCase;

/**
 * @requires extension redis
 */
class RedisTest extends TestCase
{
    /**
     * Test redis store
     */
    public function testRedisStore()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new RedisStore();
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "Redis bar");
        $this->assertEquals("Redis bar", $cache->get('foo'));
    }

    public function testRedisStorePersistence()
    {
        // Create the $cache object using the default memcache server config
        $cacheStore = new RedisStore();
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("Redis bar", $cache->get('foo'));
    }

    public function testMultipleRedisStore()
    {
        // Create two $cache object
        $cacheStore = new RedisStore();
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

    public function testTagsFlush()
    {
        // Get store
        $cacheStore = new RedisStore();
        $cache = $cacheStore->instance();

        // Start by not using tags
        $cache->put('test', "123", 60);
        $this->assertEquals("123", $cache->get('test'));
        $this->assertTrue($cache->flush());
        $this->assertNull($cache->get('test'));

        // Try again with tags
        $cache->tags('blah')->put('blah', "321", 60);
        $this->assertEquals("321", $cache->tags('blah')->get('blah'));
        $this->assertNull($cache->tags('blah')->flush());
        $this->assertNull($cache->tags('blah')->get('blah'));
    }
}