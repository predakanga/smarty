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
         foreach ($args as $key => $value) {
            $_attr[$key] = $value;
        } 
        $_value = $_attr['value'];
        
        return "<?php \$this->smarty->assign('$_attr[var]',$_value); ?>";
    } 
} 

?>
