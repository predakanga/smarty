<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource_uncompiled.php';

/**
 * Smarty Internal Plugin Resource PHP
 * 
 * Implements the file system as resource for PHP templates
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_PHP extends Smarty_Resource_Uncompiled {
    /**
     * container for short_open_tag directive's value before executing PHP templates
     * @var string
     */
    protected $short_open_tag;
    
    /**
     * Create a new PHP Resource
     *
     * @param Smarty $smarty current Smarty instance
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->short_open_tag = ini_get( 'short_open_tag' );
    } 
    
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
     * @return boolean false as php resources have no timestamp
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
        // TODO: (rodneyrehm) check if loading php-resource-files is really necessary
        if (file_exists($_tfp = $_template->getTemplateFilepath())) {
            $_template->template_source = file_get_contents($_tfp);
            return true;
        } else {
            return false;
        } 
    } 

    /**
     * Render and output the template (without using the compiler)
     *
     * @param Smarty_Internal_Template $_template template object
     * @return void
     * @throws SmartyException if template cannot be loaded or allow_php_templates is disabled
     */
    public function renderUncompiled(Smarty_Internal_Template $_template)
    {
        $_smarty_template = $_template;
        if (!$this->smarty->allow_php_templates) {
            throw new SmartyException("PHP templates are disabled");
        } 
        if ($this->getTemplateFilepath($_smarty_template) === false) {
            throw new SmartyException("Unable to load template \"{$_smarty_template->resource_type} : {$_smarty_template->resource_name}\"");
        } 
        // prepare variables
        $_smarty_ptr = $_smarty_template;
        do {
            foreach ($_smarty_ptr->tpl_vars as $_smarty_var => $_smarty_var_object) {
                if (isset($_smarty_var_object->value)) {
                    $$_smarty_var = $_smarty_var_object->value;
                } 
            } 
            $_smarty_ptr = $_smarty_ptr->parent;
        } while ($_smarty_ptr != null);
        unset ($_smarty_var, $_smarty_var_object, $_smarty_ptr); 
        // include PHP template with short open tags enabled
        ini_set( 'short_open_tag', '1' );
        include($this->getTemplateFilepath($_smarty_template));
        ini_set( 'short_open_tag', $this->short_open_tag );
    } 
} 

?>