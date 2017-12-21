<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/Cache
 * @license   https://github.com/userfrosting/Cache/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Cache\Driver;

use Illuminate\Cache\FileStore;
use Illuminate\Filesystem\Filesystem;

/**
 * TaggableFileStore Class
 *
 * Custom file based cache driver with supports for Tags
 * Inspired by unikent/taggedFileCache
 *
 * @author    Louis Charette
 */
class TaggableFileStore extends FileStore
{
    /**
     * @var string The separator when creating a tagged directory.
     */
	public $separator;

    /**
     * @var string The directory where the tags list is stored
     */
	public $tagRepository = "cache_tags";

    /**
     * @var string Directory separator.
     */
	public $ds = "/";

	/**
	 * Create a new file cache store instance.
	 *
	 * @param  Filesystem $files
	 * @param  string $directory
	 * @param  array $options
	 */
	public function __construct(Filesystem $files, $directory, $options)
	{
		$options = array_merge([
			'separator'=> '~#~'
		], $options);

		$this->separator = $options['separator'];
		parent::__construct($files,$directory);
	}


	/**
	 * Get the full path for the given cache key.
	 *
	 * @param  string  $key
	 * @return string
	 */
	protected function path($key)
	{
		$isTag = false;
		$split = explode($this->separator, $key);

		if(count($split) > 1)
		{
			$folder = reset($split) . $this->ds;

			if($folder === $this->tagRepository . $this->ds)
			{
				$isTag = true;
			}
			$key = end($split);
		}
		else
		{
			$key = reset($split);
			$folder = '';
		}

		if($isTag)
		{
			$hash = $key;
			$parts = [];
		} else {
			$parts = array_slice(str_split($hash = sha1($key), 2), 0, 2);
		}

		return $this->directory . $this->ds . $folder . (count($parts) > 0 ? implode($this->ds, $parts) . $this->ds : '') . $hash;
	}

	/**
	 * Begin executing a new tags operation.
	 *
	 * @param  array|mixed  $names
	 * @return TaggedFileCache
	 */
	public function tags($names)
	{
		return new TaggedFileCache($this, new FileTagSet($this, is_array($names) ? $names : func_get_args()));
	}


	/**
	 * Flush old tags path when a tag is flushed
	 *
	 * @param string $tagId
	 * @return void
	 */
	public function flushOldTag($tagId){

		foreach ($this->files->directories($this->directory) as $directory)
		{
			if (str_contains(basename($directory),$tagId))
			{
				$this->files->deleteDirectory($directory);
			}
		}
	}
}
