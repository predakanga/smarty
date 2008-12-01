<?php

/**
* Smarty method Clear_All_Cache
* 
* Empties the cache folder
* 
* @package Smarty
* @subpackage Smartymethod
* @author Uwe Tews 
*/

/**
* Smarty class Clear_All_Cache
* 
* Empties the cache folder
*/

class Smarty_Method_Clear_All_Cache extends Smarty_Internal_Base {
    /**
    * Empty cache folder
    */
    public function execute($args)
    {
        $count = 0;
        $cacheDirs = new RecursiveDirectoryIterator($this->smarty->cache_dir);
        $cache = new RecursiveIteratorIterator($cacheDirs);
        foreach ($cache as $file) {
            if ($cache->isDot() || $cache->isDir() || substr($file, -4) !== '.php') {
                continue;
            } 
            $count += unlink((string) $file) ? 1 : 0;
        } 
        return $count;
    } 
} 

?>
