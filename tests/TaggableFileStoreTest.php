<?php

/**
 * TaggableFileStoreTest
 *
 * Tests for `TaggableFileStore`
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Cache;

use PHPUnit\Framework\TestCase;

class TaggableFileStoreTest extends TestCase
{
    public $storage;

    public function setup() {
        $this->storage = "./tests/cache";
    }

    /**
     * Test file store
     */
    public function testTaggableFileStore()
    {
        // Create the $cache object
        $cacheStore = new TaggableFileStore($this->storage);
        $cache = $cacheStore->instance();

        // Store "foo" and try to read it
        $cache->forever("foo", "bar");
        $this->assertEquals("bar", $cache->get('foo'));
    }

    public function TaggableFileStorePersistence()
    {
        // Create the $cache object
        $cacheStore = new TaggableFileStore($this->storage);
        $cache = $cacheStore->instance();

        // Doesn't store anything, just tried to read the last one
        $this->assertEquals("bar", $cache->get('foo'));
    }

    public function testMultipleTaggableFileStore()
    {
        // Create two $cache object
        $cacheStore = new TaggableFileStore($this->storage);
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

    public function testMultipleTaggableFileStoreWithTags()
    {
        // Create two $cache object
        $cacheStore = new TaggableFileStore($this->storage);
        $cache = $cacheStore->instance();

        // Store stuff in first
        $cache->tags(['foo', 'red'])->forever("bar", "red");

        // Store stuff in second
        $cache->tags(['foo', 'blue'])->forever("bar", "blue");

        // Flush first
        $cache->tags('red')->flush();

        // First show be empty, but not the second one
        //$this->assertEquals(null, $cache->tags(['foo', 'red'])->get('bar'));
        $this->assertEquals('blue', $cache->tags(['foo', 'blue'])->get('bar'));
    }

    public function testFlushingTaggableFileStore()
    {
        // Create two $cache object
        $cacheStore = new TaggableFileStore($this->storage);
        $cache = $cacheStore->instance();
        $cache->flush();
    }
}