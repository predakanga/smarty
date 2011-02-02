<?php

require_once dirname(__FILE__) . '/cacheresource.mysql.php';

class Smarty_CacheResource_Mysqltest extends Smarty_CacheResource_Mysql {   
    public function __sleep()
    {
        return array();
    }
    
    public function __wakeup()
    {
        $this->__construct();
    }
}