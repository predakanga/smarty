<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// compiles the {assign } tag to php
class Smarty_Internal_Compile_Assign extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->required_attributes = array('var', 'value');
        $this->optional_attributes = array('nocache', 'global'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $nocache = 'false';
        $global = 'false';

        if ($_attr['nocache'] == 'true') {
            $nocache = 'true';
        } 
        if ($_attr['global'] == 'true') {
            $global = 'true';
        } 
        // remember mark nocache for the compiler
        $this->tpl_vars[$_attr['var']]->nocache = $nocache;
        return "<?php \$this->assign($_attr[var],$_attr[value],$nocache,$global);?>";
    } 
} 

?>
