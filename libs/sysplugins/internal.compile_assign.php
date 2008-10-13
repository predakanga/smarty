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
        $this->optional_attributes = array('nocache');
        
         // check and get attributes
        $_attr = $this->_get_attributes($args);
 
        if (isset($_attr['nocache']) || $this->_smarty_caching == false) {
           // remember this for the compiler
           $this->smarty->tpl_vars[$_attr['var']]->caching = false;        
           return "<?php \$this->smarty->assign($_attr[var],$_attr[value],false);?>\n";
        } else {
           // remember this for the compiler
           $this->smarty->tpl_vars[$_attr['var']]->caching = true;        
           return "<?php \$this->smarty->assign($_attr[var],$_attr[value]);?>\n";
       }
    } 
} 

?>
