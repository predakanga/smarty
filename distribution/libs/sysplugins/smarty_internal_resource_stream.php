<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Internal Plugin Resource Stream
 * 
 * Implements the streams as resource for Smarty template
 * 
 * @see http://php.net/streams
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_Stream extends Smarty_Resource {
    /**
	 * stream-resources are evaluated by default
	 * @var boolean
	 */
    public $isEvaluated = true;

    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public function isExisting(Smarty_Internal_Template $template)
    {
        if ($template->getTemplateSource() == '') {
            return false;
        } else {
            return true;
        } 
    } 

    /**
     * Get filepath to template source
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string filepath (stream URI) to template source file
     */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    { 
        // no filepath for strings
        // return resource name for compiler error messages
        return str_replace(':', '://', $_template->template_resource);
    } 

    /**
     * Get timestamp to template source
     * 
     * @param object $_template template object
     * @return boolean false as string resources have no timestamp
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template)
    { 
        // strings must always be compiled and have no timestamp
        return false;
    } 

    /**
     * Load template's source from stream into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    { 
        // return template string
        $_template->template_source = '';
        $fp = fopen(str_replace(':', '://', $_template->template_resource),'r+');
        // TODO: (rodneyrehm) stream may not be openable, handle errors
        while (!feof($fp)) {
            $_template->template_source .= fgets($fp);
        } 
        fclose($fp);

        return true;
    } 
} 

?>