<?php
/**
* Smarty Internal Plugin Compile Block Close
*
* Compiles the {/capture} tag 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Compile BlockClose Class
*/ 
class Smarty_Internal_Compile_BlockClose extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {/block} tag
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler; 
        $this->required_attributes = array('id'); 
        
       // check and get attributes
        $_attr = $this->_get_attributes($args);
        // this tag does not return compiled code but as an exception
        // the logic of {block} tag processing requires this
        $this->compiler->has_code = true;

        $saved_attr = $this->_close_tag(array('block'));

        return true;
    } 
} 

?>
