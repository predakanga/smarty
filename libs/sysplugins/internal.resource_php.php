<?php

/**
* Smarty Internal Plugin Resource PHP
* 
* Implements the file system as resource for PHP templates
* @package Smarty
* @subpackage TemplateResources
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Resource PHP
*/
class Smarty_Internal_Resource_PHP extends Smarty_Internal_Base {
    /**
    * Get filepath to template source
    * 
    * @param object $_template template object
    * @return string filepath to template source file
    */
    public function getTemplateFilepath($_template)
    {
        return $_template->buildTemplateFilepath ();
    } 

    /**
    * Get timestamp to template source
    * 
    * @param object $_template template object
    * @return integer timestamp of template source file
    */
    public function getTemplateTimestamp($_template)
    {
        return filemtime($_template->getTemplateFilepath());
    } 

    /**
    * Read template source from file
    * 
    * @param object $_template template object
    * @return string content of template source file
    */
    public function getTemplateSource($_template)
    {
        if (file_exists($_template->getTemplateFilepath())) {
            $_template->template_source = file_get_contents($_template->getTemplateFilepath());
            return true;
        } else {
            return false;
        } 
    } 

    /**
    * Return flag that this resource not use the compiler
    * 
    * @return boolean false
    */
    public function usesCompiler()
    { 
        // does not use compiler, template is PHP
        return false;
    } 

    /**
    * Return flag that this is not evaluated
    * 
    * @return boolean false
    */
    public function isEvaluated()
    { 
        // does not use compiler, must be false
        return false;
    } 
} 

?>
