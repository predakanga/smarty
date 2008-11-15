<?php

/**
* Smarty Internal Plugin CacheResource File
* 
* Implements the file system as resource for the HTML cache
* Version ussing nocache inserts
* 
* @package Smarty
* @subpackage Cacher
* @author Uwe Tews 
*/

/**
* This class does contain all necessary methods for the HTML cache on file system
*/
class Smarty_Internal_CacheResource_File extends Smarty_Internal_PluginBase {
    /**
    * Returns the filepath of the cached template output
    * 
    * @param object $_template current template
    * @return string the cache filepath
    */
    public function getCachedFilepath($_template)
    {
        return $this->buildCachedFilepath ($_template);
    } 

    /**
    * Returns the timpestamp of the cached template output
    * 
    * @param object $_template current template
    * @return integer |booelan the template timestamp or false if the file does not exist
    */
    public function getCachedTimestamp($_template)
    {
        return file_exists($_template->getCachedFilepath()) ? filemtime($_template->getCachedFilepath()) : 0 ;
    } 

    /**
    * Returns the cached template output
    * 
    * @param object $_template current template
    * @return string |booelan the template content or false if the file does not exist
    */
    public function getCachedContents($_template)
    {
        return file_get_contents($_template->getCachedFilepath());
    } 

    /**
    * Writes the rendered template output to cache file
    * 
    * @param object $_template current template
    * @return boolean status
    */
    public function writeCachedContent($_template)
    {
        if (!is_object($_template->write_file_object)) {
            $this->smarty->loadPlugin("Smarty_Internal_Write_File");
            $_template->write_file_object = new Smarty_Internal_Write_File;
        } 
        $_template->write_file_object->writeFile($_template->getCachedFilepath(), $_template->cached_template);
    } 

    /**
    * Get system filepath to cached file
    * 
    * @param object $_template current template
    * @return string filepath of cache file
    */
    private function buildCachedFilepath ($_template)
    {
        $_filepath = md5($_template->resource_name); 
        // if use_sub_dirs, break file into directories
        if ($this->smarty->use_sub_dirs) {
            $_filepath = substr($_filepath, 0, 3) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 2) . DIRECTORY_SEPARATOR
             . substr($_filepath, 0, 1) . DIRECTORY_SEPARATOR
             . $_filepath;
        } 
        $_compile_dir_sep = $this->smarty->use_sub_dirs ? DIRECTORY_SEPARATOR : '^';
        if (isset($_template->cache_id)) {
            $_cache_id = str_replace('|', $_compile_dir_sep, $_template->cache_id);
            $_filepath = $_cache_id . $_compile_dir_sep . $_filepath;
        } 
        if (isset($_template->compile_id)) {
            $_filepath = $_template->compile_id . $_compile_dir_sep . $_filepath;
        } 

        return $this->smarty->cache_dir . $_filepath . '.' . $_template->resource_name . $this->smarty->php_ext;
    } 
} 

?>
