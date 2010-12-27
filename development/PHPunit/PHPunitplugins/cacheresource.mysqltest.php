<?php

require_once dirname(__FILE__) . '/cacheresource.mysql.php';

class Smarty_CacheResource_Mysqltest extends Smarty_CacheResource_Mysql {
    public function get(Smarty_Internal_Template $_template)
    {
        $id = $this->buildCachedFilepath($_template->resource_name, $_template->cache_id, $_template->compile_id);
        $this->fetch($id, $_template->resource_name, $_template->cache_id, $_template->compile_id, $content, $mtime);
        return $content ? $this->decodeCache($_template, $content) : null;
    }
}