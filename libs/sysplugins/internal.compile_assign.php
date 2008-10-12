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

        if (isset($_attr['nocache'])) {        
           return "<?php \$this->smarty->assign('$_attr[var]',$_attr[value],false);?>";
        } else {
           return "<?php \$this->smarty->assign('$_attr[var]',$_attr[value]);?>";
       }
    } 
} 

?>
