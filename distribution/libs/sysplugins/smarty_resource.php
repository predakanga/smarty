<?php

/**
 * Smarty Resource Plugin
 * 
 * Base implementation for resource plugins
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */
abstract class Smarty_Resource {
	/**
	 * Name of the Class to compile this resource's contents with
	 * @var string
	 */
    public $compiler_class = 'Smarty_Internal_SmartyTemplateCompiler';

	/**
	 * Name of the Class to tokenize this resource's contents with
	 * @var string
	 */
    public $template_lexer_class = 'Smarty_Internal_Templatelexer';

    /**
	 * Name of the Class to parse this resource's contents with
	 * @var string
	 */
    public $template_parser_class = 'Smarty_Internal_Templateparser';

    /**
	 * Flag stating if this resource's contents have to be compiled or not
	 * @var boolean
	 */
    public $usesCompiler = true;
    
    /**
	 * Flag stating if this resource's contents have already been evaluated
	 * @var boolean
	 */
    public $isEvaluated = false;
    
    /**
     * Create a new Resource
     *
     * @param Smarty $smarty current Smarty instance
     */
    public function __construct($smarty)
    {
        $this->smarty = $smarty;
    }
    
    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     */
    public abstract function isExisting(Smarty_Internal_Template $template);
    
    /**
    * Get filepath to template source
    * 
    * @param Smarty_Internal_Template $_template template object
    * @return string filepath to template source file
    */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    {
        $_filepath = $_template->buildTemplateFilepath();

        if ($_filepath !== false) {
            if (is_object($_template->smarty->security_policy)) {
                $_template->smarty->security_policy->isTrustedResourceDir($_filepath);
            } 
        } 
        $_template->templateUid = sha1($_filepath);
        return $_filepath;
    }
    
    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return integer|boolean timestamp (epoch) the template was modified, false if resources has no timestamp
     */
    public abstract function getTemplateTimestamp(Smarty_Internal_Template $_template);
    
    /**
     * Load template's source into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     */
    public abstract function getTemplateSource(Smarty_Internal_Template $_template);
    
    /**
     * Get filepath to compiled template
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string|boolean path to compiled template or false if not applicable
     */
    public function getCompiledFilepath(Smarty_Internal_Template $_template)
    {
        return false;
    }
    
    /**
     * Build filepath to compiled template
     *
     * @param Smarty_Internal_Template $_template template object
     * @param string $_basename basename of the template to inject into the filepath
     * @return string path to compiled template
     */
    protected function buildCompiledFilepath(Smarty_Internal_Template $_template, $_basename)
    {
        $_compile_id = isset($_template->compile_id) ? preg_replace('![^\w\|]+!', '_', $_template->compile_id) : null;
        // calculate Uid if not already done
        if ($_template->templateUid == '') {
            $_template->getTemplateFilepath();
        } 
        $_filepath = $_template->templateUid; 
        // if use_sub_dirs, break file into directories
        if ($_template->smarty->use_sub_dirs) {
            $_filepath = substr($_filepath, 0, 2) . DS
             . substr($_filepath, 2, 2) . DS
             . substr($_filepath, 4, 2) . DS
             . $_filepath;
        } 
        $_compile_dir_sep = $_template->smarty->use_sub_dirs ? DS : '^';
        if (isset($_compile_id)) {
            $_filepath = $_compile_id . $_compile_dir_sep . $_filepath;
        } 
        // caching token
        if ($_template->caching) {
            $_cache = '.cache';
        } else {
            $_cache = '';
        }
        $_compile_dir = $_template->smarty->compile_dir;
        if (strpos('/\\', substr($_compile_dir, -1)) === false) {
            $_compile_dir .= DS;
        }
        // separate (optional) basename by dot
        if ($_basename) {
            $_basename = '.' . $_basename;
        }
        return $_compile_dir . $_filepath . '.' . $_template->resource_type . $_basename . $_cache . '.php';
    }
}

?>