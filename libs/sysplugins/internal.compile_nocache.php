<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// Handles the {nocache} tags
// It does not generate compiled code, just sets the compile status flags 
class Smarty_Internal_Compile_Nocache extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->compiler->_compiler_status->nocache = true;
        return ' ';
    } 
} 
class Smarty_Internal_Compile_End_Nocache extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->compiler->_compiler_status->nocache = false;
        return ' ';
    } 
} 

?>
