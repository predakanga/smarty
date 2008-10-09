<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_File extends Smarty_Internal_Base {

    public function getFilepath($template_resource)
    {
        return $this->smarty->getTemplateFilepath($template_resource);
    }

    public function getTimestamp($resource_name)
    {
        $_tpl_filepath = $this->smarty->getTemplateFilepath($resource_name);
        return file_exists($_tpl_filepath) ? filemtime($_tpl_filepath) : false;
    } 

    public function getContents($resource_name)
    { 
        // read template file
        $_tpl_filepath = $this->smarty->getTemplateFilepath($resource_name);
        return file_exists($_tpl_filepath) ? file_get_contents($_tpl_filepath) : false;
    }
    
    public function usesCompiler()
    { 
        // template has tags, uses compiler
        return true;
    }
    
    public function isEvaluated()
    { 
        // save the compiled file to disk, do not eval
        return false;
    } 
    
     
} 

?>
