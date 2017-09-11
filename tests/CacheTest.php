<?php

/**
 * CacheTest
 *
 * Tests for `ArrayStore` and `FileStore`
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Cache;

use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    public $storage;

    public function setup() {
        $this->storage = "./tests/cache/FileStore";
    }

    /**
     * Test basic array store
     */
    public function testArrayStore()
    {
        // Create the $cache object
        $cacheStore = new ArrayStore();
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "array");
        $this->assertEquals("array", $cache->get('foo'));
    }

    public function testArrayStorePersistence()
    {
        // Create the $cache object
        $cacheStore = new ArrayStore();
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
        $cacheStore = new FileStore($this->storage);
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "bar");
        $this->assertEquals("bar", $cache->get('foo'));
    }

    public function testFileStorePersistence()
    {
        // Create the $cache object
        $cacheStore = new FileStore($this->storage);
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("bar", $cache->get('foo'));
    }

    /*public function testReuseApp()
    {
        $app = new \Illuminate\Container\Container();

        // Create two $cache object
        $cacheStore = new FileStore("global", $this->storage, $app);
        $cacheGlobal = $cacheStore->instance();

        $cacheStore2 = new FileStore("user2419", $this->storage, $app);
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
    }*/
}
