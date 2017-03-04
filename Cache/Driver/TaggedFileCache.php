<?php

/**
 * TaggedFileCache Class
 *
 * Custom file based cache driver with supports for Tags
 * Inspired by unikent/taggedFileCache
 *
 * @package   userfrosting/Cache
 * @link      https://github.com/userfrosting/Cache
 * @author    Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Cache\Driver;

use Illuminate\Cache\TaggedCache;

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
