<?php

/**
* Smarty Internal Plugin Resource File
* 
* Implements the file system as resource for Smarty templates
* @package Smarty
* @subpackage Template Resources
* @author Uwe Tews
*/

class Smarty_Internal_Resource_File extends Smarty_Internal_Base {
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
        // read template file
        if (file_exists($_template->getTemplateFilepath())) {
            $_template->template_source = file_get_contents($_template->getTemplateFilepath());
            return true;
        } else {
            return false;
        } 
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
