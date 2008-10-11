<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
/**
* Cache Handler
*/

class Smarty_Internal_Caching_File extends Smarty_Internal_PluginBase {
    public function getCachedFilepath($_template)
    {
        return $this->buildCachedFilepath ($_template);
    } 

    public function getTimestamp($_template)
    {
        return file_exists($_template->getCachedFilepath()) ? filemtime($_template->getCachedFilepath()) : 0 ;
    } 

    public function getContents($_template)
    { 
        // read cached template file
        return file_get_contents($_template->getCachedFilepath());
    } 

    public function writeContent($_template)
    {
        if (!is_object($_template->write_file_object)) {
            $this->smarty->loadPlugin("Smarty_Internal_Write_File");
            $_template->write_file_object = new Smarty_Internal_Write_File;
        } 
        $_template->write_file_object->writeFile($_template->getCachedFilepath(), $_template->rendered_template);
    } 

    /*
     * get system filepath to cached file
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

        return $this->smarty->cache_dir . $_filepath . '.' . $_template->resource_name . $this->smarty->php_ext;
    } 
} 

?>
