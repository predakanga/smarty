<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Resource Plugin
 * 
 * Base implementation for resource plugins that don't use the compiler
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource_Uncompiled extends Smarty_Resource {
    /**
	 * Uncompiled Resources cannot use the compiler
	 * @var boolean
	 */
    public $usesCompiler = false;

    /**
     * Render and output the template (without using the compiler)
     *
     * @param Smarty_Internal_Template $_template template object
     * @return void
     * @throws SmartyException 
     */
    public abstract function renderUncompiled(Smarty_Internal_Template $_template);
    
    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean always false as uncompiled resources are not saved to compile cache
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return false;
    }
}

?>