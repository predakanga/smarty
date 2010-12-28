<?php

require_once dirname(__FILE__) . '/cacheresource.memcache.php';

class Smarty_CacheResource_Memcachetest extends Smarty_CacheResource_Memcache {
    public function get(Smarty_Internal_Template $_template)
    {
        $this->contents = array();
        $this->timestamps = array();
        $t = $this->getCachedContents($_template);
        return $t ? $t : null;
    }
    
    public function __sleep()
    {
        return array();
    }
    
    public function __wakeup()
    {
        $this->__construct();
    }
}