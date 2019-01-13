<?php
/**
 * UserFrosting Cache (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/cache
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/cache/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Cache\Driver;

use Illuminate\Cache\TaggedCache;

/**
 * TaggedFileCache Class
 *
 * Custom file based cache driver with supports for Tags
 * Inspired by unikent/taggedFileCache
 *
 * @author    Louis Charette
 */
class TaggedFileCache extends TaggedCache
{
    /**
     * {@inheritdoc}
     */
    public function taggedItemKey($key)
    {
        return $this->tags->getNamespace() . $this->store->separator . $key;
    }
}
