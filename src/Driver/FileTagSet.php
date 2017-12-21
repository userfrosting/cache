<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/Cache
 * @license   https://github.com/userfrosting/Cache/blob/master/licenses/UserFrosting.md (MIT License)
 */
 namespace UserFrosting\Cache\Driver;

 use Illuminate\Cache\TagSet;

/**
 * FileTagSet Class
 *
 * Custom file based cache driver with supports for Tags
 * Inspired by unikent/taggedFileCache
 *
 * @author    Louis Charette
 */
class FileTagSet extends TagSet
{
    /**
     *    @var string Driver name
     */
	protected static $driver = 'tfile';

	/**
	 * Get the tag identifier key for a given tag.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function tagKey($name)
	{
		return $this->store->tagRepository . $this->store->separator . preg_replace('/[^\w\s\d\-_~,;\[\]\(\).]/', '~', $name);
	}

	/**
	 * Reset the tag and return the new tag identifier.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function resetTag($name)
	{
        // Get the old tagId. When reseting a tag, a new id will be create
        $oldID = $this->store->get($this->tagKey($name));

        if ($oldID !== false)
        {
    		$oldIDArray = is_array($oldID) ? $ids : [$oldID];
    		foreach($oldIDArray as $id)
    		{
        		$this->store->flushOldTag($id);
        	}
        }

        return parent::resetTag($name);
	}
}
