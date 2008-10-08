<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_File extends Smarty_Internal_Base {

    public function getFilePathes ($resource_name, &$_tpl_filepath, &$_compiled_filepath)
    {

        $_tpl_filepath = $this->smarty->getTemplateFilepath($resource_name);
        $_compiled_filepath = $this->smarty->getCompileFilepath($_tpl_filepath);
    }    
 
    public function getTimestamp ($_tpl_filepath)
    {
        return file_exists($_tpl_filepath) ? filemtime($_tpl_filepath) : false;
    } 

    public function getTemplate($_tpl_filepath)
    { 
        // read template file
        return file_exists($_tpl_filepath) ? file_get_contents($_tpl_filepath) : false;
    } 
} 

?>
