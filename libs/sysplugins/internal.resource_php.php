<?php

/**
* Smarty Internal Plugin Resource PHP
* 
* Implements the file system as resource for PHP templates
* @package Smarty
* @subpackage Template Resources
* @author Uwe Tews
*/

class Smarty_Internal_Resource_PHP extends Smarty_Internal_Base {
    public function getTemplateFilepath($_template)
    {
        return $_template->buildTemplateFilepath ();
    } 

    public function getTemplateTimestamp($_template)
    {
        return filemtime($_template->getTemplateFilepath());
    } 

    public function getTemplateSource($_template)
    {
        if (file_exists($_template->getTemplateFilepath())) {
            $_template->template_source = file_get_contents($_template->getTemplateFilepath());
            return true;
        } else {
            return false;
        } 
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
