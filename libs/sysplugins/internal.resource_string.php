<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_String extends Smarty_Internal_Base {

    public function getTemplateFilepath($_template)
    {
        // no filepath for strings
        // return "string" for compiler error messages
        return '"string"';;
    }

    public function getTimestamp($_template)
    {    
         // strings are always compiled
         return false;
    } 

    public function getContents($_template)
    { 
        // return template string
        return $_template->resource_name;
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
