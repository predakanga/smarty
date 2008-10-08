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

        // check and get attributes
        $_attr = $this->_get_attributes($args);
        
        return "<?php \$_smarty->assign('$_attr[var]',$_attr[value]);?>";
    } 
} 

?>
