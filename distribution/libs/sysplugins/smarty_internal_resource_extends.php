<?php

// TODO: (rodneyrehm) extend autoloader to load this
require_once SMARTY_SYSPLUGINS_DIR . 'smarty_resource.php';

/**
 * Smarty Internal Plugin Resource Extends
 * 
 * Implements the file system as resource for Smarty which {extend}s a chain of template files templates
 * 
 * @package Smarty
 * @subpackage TemplateResources
 * @author Uwe Tews 
 */
class Smarty_Internal_Resource_Extends extends Smarty_Resource {
    /**
     * Right Delimiter
     * @var string
     */
    protected $_rdl;
    
    /**
     * Left Delimiter
     * @var string
     */
     
    protected $_ldl;

    /**
     * Create a new {extend} Resource handler
     *
     * @param Smarty $smarty current Smarty instance
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->_rdl = preg_quote($smarty->right_delimiter);
        $this->_ldl = preg_quote($smarty->left_delimiter);
    } 
    
    /**
     * Container for indivual templates being extended
     * @var array
     */
    protected $allFilepaths = array();

    /**
     * Test if the template source exists
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return boolean true if exists, false else
     * @uses $allFilepaths to verify each individual file
     */
    public function isExisting(Smarty_Internal_Template $_template)
    {
        $_template->getTemplateFilepath();
        foreach ($this->allFilepaths as $_filepath) {
            if ($_filepath === false) {
                return false;
            }
        }
        return true;
    } 

    /**
     * Get filepath to template source
     * 
     * @param Smarty_Internal_Template $_template template object
     * @return string filepath to template source file
     * @uses $allFilepaths to register each individual file
     */
    public function getTemplateFilepath(Smarty_Internal_Template $_template)
    {
        $sha1String = '';
        $_files = explode('|', $_template->resource_name);
        foreach ($_files as $_file) {
            $_filepath = $_template->buildTemplateFilepath($_file);
            if ($_filepath !== false) {
                if (is_object($_template->smarty->security_policy)) {
                    $_template->smarty->security_policy->isTrustedResourceDir($_filepath);
                } 
            } 
            $sha1String .= $_filepath;
            $this->allFilepaths[$_file] = $_filepath;
        } 
        $_template->templateUid = sha1($sha1String);
        return $_filepath;
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
     * Load template's source from files into current template object
     * 
     * @note: The loaded source is assigned to $_template->template_source directly.
     * @param Smarty_Internal_Template $_template current template
     * @return boolean success: true for success, false for failure
     * @throws SmartyException if unable to load a file
     */
    public function getTemplateSource(Smarty_Internal_Template $_template)
    {
        $this->template = $_template;
        $_files = array_reverse($this->allFilepaths);
        $_first = reset($_files);
        $_last = end($_files);
        foreach ($_files as $_file => $_filepath) {
            if ($_filepath === false) {
                throw new SmartyException("Unable to load template 'file : {$_file}'");
            }
            // read template file
            if ($_filepath != $_first) {
                $_template->properties['file_dependency'][sha1($_filepath)] = array($_filepath, filemtime($_filepath),'file');
            } 
            $_template->template_filepath = $_filepath;
            $_content = file_get_contents($_filepath);
            if ($_filepath != $_last) {
                if (preg_match_all("!({$this->_ldl}block\s(.+?){$this->_rdl})!", $_content, $_open) !=
                        preg_match_all("!({$this->_ldl}/block{$this->_rdl})!", $_content, $_close)) {
                    $this->smarty->triggerError("unmatched {block} {/block} pairs in file '$_filepath'");
                } 
                preg_match_all("!{$this->_ldl}block\s(.+?){$this->_rdl}|{$this->_ldl}/block{$this->_rdl}!", $_content, $_result, PREG_OFFSET_CAPTURE);
                $_result_count = count($_result[0]);
                $_start = 0;
                while ($_start < $_result_count) {
                    $_end = 0;
                    $_level = 1;
                    while ($_level != 0) {
                        $_end++;
                        if (!strpos($_result[0][$_start + $_end][0], '/')) {
                            $_level++;
                        } else {
                            $_level--;
                        } 
                    } 
                    $_block_content = str_replace($this->smarty->left_delimiter . '$smarty.block.parent' . $this->smarty->right_delimiter, '%%%%SMARTY_PARENT%%%%',
                        substr($_content, $_result[0][$_start][1] + strlen($_result[0][$_start][0]), $_result[0][$_start + $_end][1] - $_result[0][$_start][1] - + strlen($_result[0][$_start][0])));
                    Smarty_Internal_Compile_Block::saveBlockData($_block_content, $_result[0][$_start][0], $_template, $_filepath);
                    $_start = $_start + $_end + 1;
                } 
            } else {
                $_template->template_source = $_content;
                return true;
            } 
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
        $p = strrpos($_template->resource_name, '|');
        $_basename = basename($p !== false ? substr($_template->resource_name, $p +1 ) : $_template->resource_name );
        return $this->buildCompiledFilepath($_template, $_basename );
    } 
} 

?>