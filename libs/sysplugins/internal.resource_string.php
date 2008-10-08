<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_String extends Smarty_Internal_Base {

    public function getFilePathes ($resource_name, &$_tpl_filepath, &$_compiled_filepath)
    {

        $_tpl_filepath = $resource_name;
        $_compiled_filepath = $this->smarty->getCompileFilepath($_tpl_filepath);
    }    


    public function getTimestamp ($_tpl_filepath)
    {
         return 0;
    } 

    public function getTemplate($_tpl_filepath)
    { 
        // return template string
        return $_tpl_filepath;
    } 
} 

?>
