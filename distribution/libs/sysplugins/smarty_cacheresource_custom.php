<?php

/**
 * Cache Handler API
 *
 * @package Smarty
 * @subpackage Cacher
 * @author Rodney Rehm
 */
abstract class Smarty_CacheResource_Custom extends Smarty_CacheResource {
    /**
     * cache results of fetch() calls
     * @var array
     */
    protected $cache = array();
    
    /**
     * fetch and cache template source and mtime
     *
     * @param string $id unique cache content identifier
     * @param string $name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @return array template data array('mtime' => …, 'source' => …)
     */
    protected function cache($id, $name, $cache_id, $compile_id)
    {
        if (!isset($this->cache[$id])){
            $this->fetch($id, $cache_id, $compile_id, $content, $mtime);
            $this->cache[$name] = array(
                'mtime' => $mtime,
                'content' => $content,
            );
        }
        return $this->cache[$id];
    }
    
    /**
     * fetch cached content and its modification time from data source
     *
     * @param string $id unique cache content identifier
     * @param string $name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @param string $content cached content
     * @param integer $mtime cache modification timestamp (epoch)
     * @return void
     */
    protected abstract function fetch($id, $name, $cache_id, $compile_id, &$content, &$mtime);
    
    /**
     * Fetch cached content's modification timestamp from data source
     *
     * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the complete cached content.
     * @param string $id unique cache content identifier
     * @param string $name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @return integer|boolean timestamp (epoch) the template was modified, or false if not found
     */
    protected function fetchTimestamp($id, $name, $cache_id, $compile_id)
    {
        return null;
    }
    
    /**
     * Save content to cache
     *
     * @param string $id unique cache content identifier
     * @param string $name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @param integer|null $exp_time seconds till expiration or null
     * @param string $content content to cache
     * @return boolean success
     */
    protected abstract function save($id, $name, $cache_id, $compile_id, $exp_time, $content);
    
    /**
     * Delete content from cache
     *
     * @param string $name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @param integer|null $exp_time seconds till expiration time in seconds or null
     * @return integer number of deleted caches
     */
    protected abstract function delete($name, $cache_id, $compile_id, $exp_time);
    
    
    /**
	 * Determine the filepath (or some unique cache id) of the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @return string the cache filepath
	 */
	public function getCachedFilepath(Smarty_Internal_Template $_template)
    {
        return $this->buildCachedFilepath($_template->source->name, $_template->cache_id, $_template->compile_id);
    }
    
    /**
	 * Determine the timpestamp (epoch) of the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @return integer|booelan the template timestamp (epoch), or false if the file does not exist
	 */
	public function getCachedTimestamp(Smarty_Internal_Template $_template)
	{
	    $id = $this->buildCachedFilepath($_template->source->name, $_template->cache_id, $_template->compile_id);
        $mtime = $this->fetchTimestamp($id, $_template->source->name, $_template->cache_id, $_template->compile_id);
        if ($mtime !== null) {
            return $mtime;
        }
        $t = $this->cache($id, $_template->source->name, $_template->cache_id, $_template->compile_id);
        return isset($t['mtime']) ? $t['mtime'] : false;
	}
	
	/**
	 * Get the cached template output
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param boolean $no_render true to echo content immediately, false to return content as string
	 * @return string|booelan the template content, or false if the file does not exist
	 */
	public function getCachedContents(Smarty_Internal_Template $_template, $no_render = false)
	{
        $t = $this->cache(
	        $this->buildCachedFilepath($_template->source->name, $_template->cache_id, $_template->compile_id),
            $_template->source->name, 
            $_template->cache_id, 
            $_template->compile_id
        );
        if( isset($t['content']) ) {
            return $this->decodeCache($_template, $t['content'], $no_render);
        }
        return false;
	}
	
	/**
	 * Write the rendered template output to cache
	 * 
	 * @param Smarty_Internal_Template $_template template object
	 * @param string $content content to cache
	 * @return boolean status
	 */
	public function writeCachedContent(Smarty_Internal_Template $_template, $content)
	{
	    return $this->save(
	        $this->buildCachedFilepath($_template->source->name, $_template->cache_id, $_template->compile_id),
	        $_template->source->name, 
	        $_template->cache_id, 
	        $_template->compile_id, 
	        $this->getCacheLifetime($_template),
	        $content
	    );
	}
	
	/**
	 * Empty cache
	 * 
     * @param Smarty $smarty Smarty object
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	 */
	public function clearAll(Smarty $smarty, $exp_time=null)
	{
        $this->cache = array();
	    return $this->delete( null, null, null, $exp_time );
	}
    
    /**
	 * Empty cache for a specific template
	 * 
     * @param Smarty $smarty Smarty object
	 * @param string $resource_name template name
	 * @param string $cache_id cache id
	 * @param string $compile_id compile id
	 * @param integer $exp_time expiration time (number of seconds, not timestamp)
	 * @return integer number of cache files deleted
	*/
	public function clear(Smarty $smarty, $resource_name, $cache_id, $compile_id, $exp_time)
    {
        $this->cache = array();
	    return $this->delete( $resource_name, $cache_id, $compile_id, $exp_time );
    }
	
	/**
	 * Build filepath to cache
	 *
	 * @param string $resource_name template name
	 * @param string $cache_id cache id
	 * @param string $compile_id compile id
	 * @return string unique cache id
	 */
	protected function buildCachedFilepath($resource_name, $cache_id, $compile_id)
    {
        $_cache_id = isset($cache_id) ? preg_replace('![^\w\|]+!', '_', $cache_id) : null;
        $_compile_id = isset($compile_id) ? preg_replace('![^\w\|]+!', '_', $compile_id) : null;
        return sha1($resource_name . $_cache_id . $_compile_id);
    }
}