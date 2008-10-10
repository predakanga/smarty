<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_Resource_PHP extends Smarty_Internal_Base {

    public function getTemplateFilepath($_template)
    {
        return $_template->buildTemplateFilepath ();
    }

    public function getTimestamp($_template)
    {
            // no time stamps for PHP templates
            return false;
    } 

    public function getContents($_template)
    { 
        // is no template source to compile
        return false;
    } 

    public function usesCompiler()
    { 
        // does not use compiler, template is PHP
        return false;
    } 

    public function isEvaluated()
    { 
        // does not use compiler
        return false;
    } 
    

} 

?>
