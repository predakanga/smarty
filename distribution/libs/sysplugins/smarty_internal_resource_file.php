<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Internal Plugin Resource File
 * 
 * Implements the file system as resource for Smarty templates
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_File extends Smarty_Resource {
    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public function isExisting(Smarty_Internal_Template $template)
    {
        if ($template->getTemplateFilepath() === false) {
            return false;
        } else {
            return true;
        } 
    } 

    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return integer timestamp (epoch) the template was modified
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template)
    {
        return filemtime($_template->getTemplateFilepath());
    } 

    /**
     * Load template's source from file into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    { 
        // read template file
        if (file_exists($_tfp = $_template->getTemplateFilepath())) {
            $_template->template_source = file_get_contents($_tfp);
            return true;
        } else {
            return false;
        } 
    } 

    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string path to compiled template
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return $this->buildCompiledFilepath($_template, basename($_template->resource_name));
    } 
} 

?>