<?php

/**
* Smarty Internal Plugin Compile Nocache
*
* Compiles the {nocache} tag 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Compile Nocache Class
*/ 
class Smarty_Internal_Compile_Nocache extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {nocache} tag
    *
    * This tag does not generate compiled output. It only sets a compiler flag 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    public function compile($args)
    {
        // enter nocache mode
        $this->compiler->_compiler_status->nocache = true;
        // this tag does not return compiled code
        $this->compiler->has_code = false;
        return true;
    } 
} 
/**
* Smarty Internal Plugin Compile NocacheClose Class
*/ 
class Smarty_Internal_Compile_NocacheClose extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {/nocache} tag
    *
    * This tag does not generate compiled output. It only sets a compiler flag 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    public function compile($args)
    {
        // leave nocache mode
        $this->compiler->_compiler_status->nocache = false;
        // this tag does not return compiled code
        $this->compiler->has_code = false;
        return true;
    } 
} 

?>
