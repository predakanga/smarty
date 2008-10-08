<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_PHP extends Smarty_Internal_Base {

    public function getFilePathes ($resource_name, &$_tpl_filepath, &$_compiled_filepath)
    {
        $_tpl_filepath = $this->smarty->getTemplateFilepath($resource_name);
        $_compiled_filepath = $_tpl_filepath;
   }    

    public function getTimestamp ($_tpl_filepath)
    {
            // no time stamps for PHP templates
            return false;
    } 

    public function getTemplate($_tpl_filepath)
    { 
        // is no template source to compile
        return false;
    } 
} 

?>
