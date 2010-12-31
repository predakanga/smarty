<?php

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
        $allFilepaths = !empty($this->allFilepaths[$_template->templateUid]) ? $this->allFilepaths[$_template->templateUid] : array();
        foreach ($allFilepaths as $_filepath) {
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
        $allFilepaths = array();
        $sha1String = '';
        $_files = explode('|', $_template->resource_name);
        foreach ($_files as $_file) {
            $_filepath = $this->buildTemplateFilepath($_template, $_file);
            if ($_filepath !== false) {
                if (is_object($_template->smarty->security_policy)) {
                    $_template->smarty->security_policy->isTrustedResourceDir($_filepath);
                } 
            } 
            $sha1String .= $_filepath;
            $allFilepaths[$_file] = $_filepath;
        } 
        $_template->templateUid = sha1($sha1String);
        $this->allFilepaths[$_template->templateUid] = $allFilepaths;
        return $_filepath;
    } 

    /**
     * Get timestamp (epoch) the template source was modified
     * 
     * @param Smarty_Internal_Template $_template template object
     * @param string $resource_name name of the resource to get modification time of, if null, $_template->resource_name is used
     * @return integer timestamp (epoch) the template was modified
     */
    public function getTemplateTimestamp(Smarty_Internal_Template $_template, $_resource_name=null)
    {
        if ($_resource_name !== null) {
            throw new SmartyException("Cannot use \$_resource_name on extends-resources");
        }
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
        $_rdl = preg_quote($_template->smarty->right_delimiter);
        $_ldl = preg_quote($_template->smarty->left_delimiter);
        $allFilepaths = !empty($this->allFilepaths[$_template->templateUid]) ? $this->allFilepaths[$_template->templateUid] : array();
        $_files = array_reverse($allFilepaths);
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
                if (preg_match_all("!({$_ldl}block\s(.+?){$_rdl})!", $_content, $_open) !=
                        preg_match_all("!({$_ldl}/block{$_rdl})!", $_content, $_close)) {
                    $_template->smarty->triggerError("unmatched {block} {/block} pairs in file '$_filepath'");
                } 
                preg_match_all("!{$_ldl}block\s(.+?){$_rdl}|{$_ldl}/block{$_rdl}!", $_content, $_result, PREG_OFFSET_CAPTURE);
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
                    $_block_content = str_replace($_template->smarty->left_delimiter . '$smarty.block.parent' . $_template->smarty->right_delimiter, '%%%%SMARTY_PARENT%%%%',
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
        $_basename = basename($p !== false ? substr($_template->resource_name, $p +1 ) : $_template->resource_name);
        return $this->buildCompiledFilepath($_template, $_basename);
    } 
} 

?>