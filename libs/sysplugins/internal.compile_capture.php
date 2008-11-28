<?php
/**
* Smarty Internal Plugin Compile Capture
*
* Compiles the {capture}...{/capture} tag 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Compile Capture Class
*/ 
class Smarty_Internal_Compile_Capture extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {capture} tag
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler; 
        $this->optional_attributes = array('name', 'assign'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_open_tag('capture');

        if (isset($_attr['name']))
            $buffer = $_attr['name'];
        else
            $buffer = "'default'";

        if (isset($_attr['assign']))
            $assign = $_attr['assign'];
        else
            $assign = null;

        $_output = "<?php ob_start(); ?>";
        $this->smarty->_capture_stack[] = array($buffer, $assign);

        return $_output;
    } 
} 
/**
* Smarty Internal Plugin Compile CaptureClose Class
*/ 
class Smarty_Internal_Compile_CaptureClose extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {/capture} tag
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler; 
       // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_close_tag(array('capture'));

        list($buffer, $assign) = array_pop($this->smarty->_capture_stack);

        $_output = "<?php ";
        if (isset($assign)) {
            $_output .= " \$_smarty_tpl->assign($assign, ob_get_contents());";
        } 
        $_output .= " ob_clean(); ?>";
        return $_output;
    } 
} 

?>
