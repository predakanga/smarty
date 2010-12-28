<?php

class Smarty_CacheResource_Memcache extends Smarty_CacheResource_KeyValueStore {
    /**
     * memcache instance
     * @var Memcache
     */
    protected $memcache = null;
    
    public function __construct()
    {
        $this->memcache = new Memcache();
        $this->memcache->addServer( '127.0.0.1', 11211 );
    }
    
    /**
     * Read values for a set of keys from cache
     *
     * @param array $keys list of keys to fetch
     * @return array list of values with the given keys used as indexes
     * @return boolean true on success, false on failure
     */
    protected function read(array $keys)
    {
        return $this->memcache->get($keys);
    }
    
    /**
     * Save values for a set of keys to cache
     *
     * @param array $keys list of values to save
     * @param int $expire expiration time
     * @return boolean true on success, false on failure
     */
    protected function write(array $keys, $expire=null)
    {
        foreach ($keys as $k => $v) {
            $this->memcache->set($k, $v, 0, $expire);
        }
        return true;
    }

    /**
     * Remove values from cache
     *
     * @param array $keys list of keys to delete
     * @return boolean true on success, false on failure
     */
    protected function delete(array $keys)
    {
        foreach ($keys as $k) {
            $this->memcache->delete($k);
        }
        return true;
    }

    /**
     * Remove *all* values from cache
     *
     * @return boolean true on success, false on failure
     */
    protected function purge()
    {
        return $this->memcache->flush();
    }
}
