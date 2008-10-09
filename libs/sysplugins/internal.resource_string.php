<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_String extends Smarty_Internal_Base {

    public function getFilepath($template_resource)
    {
        // no filepath for strings
        return false;
    }

    public function getTimestamp ($_tpl_filepath)
    {    
         // strings are always compiled
         return false;
    } 

    public function getContents ($_tpl_filepath)
    { 
        // return template string
        return $_tpl_filepath;
    }
    
    public function usesCompiler()
    { 
        // resource string is template, needs compiler
        return true;
    }
    
    public function isEvaluated()
    { 
        // compiled template is evaluated instead of saved to disk
        return true;
    } 
     
} 

?>
