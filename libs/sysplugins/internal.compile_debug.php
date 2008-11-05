<?php 
/**
* Smarty Internal Plugin Compile Debug
*
* Compiles the {debug} tag 
* It opens a window the the Smarty Debugging Console
* @package Smarty
* @subpackage compiler
* @author Uwe Tews
*/
class Smarty_Internal_Compile_Debug extends Smarty_Internal_CompileBase {
    /**
    * Compile {debug ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    { 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        if (empty($this->smarty->debug_tpl)) {
            // set path to debug template from SMARTY_DIR
            $this->smarty->debug_tpl = SMARTY_DIR . 'debug.tpl';
        }
        $_debug_template = $this->smarty->debug_tpl;
        // display debug template
        $_output = "\$_smarty_tpl->smarty->loadPlugin('Smarty_Internal_Debug'); Smarty_Internal_Debug::display_debug();";
        return "<?php $_output ?>";
    } 
} 

?>
