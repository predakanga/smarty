<?php
/**
* Smarty Internal Plugin Compile Capture
* 
* Compiles the {block} tag
* 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Compile Block Class
*/
class Smarty_Internal_Compile_Block extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {block} tag
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler;
        $this->required_attributes = array('id', 'content'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_open_tag('block', $_attr['id']);

        if (isset($this->compiler->template->block_data[$_attr['id']])) {
            $_output = '<?php echo $_smarty_tpl->smarty->fetch(\'string:' . addslashes($this->compiler->template->block_data[$_attr['id']]) . '\', $_smarty_tpl); ?>';
        } else {
            $_output = '<?php echo $_smarty_tpl->smarty->fetch(\'string:' . addslashes($_attr['content']) . '\', $_smarty_tpl); ?>';
        }
        return $_output;
    } 
} 
                                                                                                                                                                                            
?>
