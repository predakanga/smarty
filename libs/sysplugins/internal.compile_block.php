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

        if (!is_null($compiler->template->block_data[$_attr['id']])) {
            $_content = $compiler->template->block_data[$_attr['id']];
        } else {
            $_content = $_attr['content'];
        }
        // check for smarty tags in $_content
        if ( strpos($_content, $this->smarty->left_delimiter)  === false) {
            // output as is
            $_output = $_content;
        } else {
            // tags in $_content will be precompiled and compiled code is returnd
            $tpl = $this->smarty->createTemplate('string:'.$_content);
            $tpl->suppressHeader = true;
            $_output = $tpl->getCompiledTemplate();
            $tpl->suppressHeader = false;
//            $_output = '<?php echo $_smarty_tpl->smarty->fetch(\'string:' . addcslashes($_content,"'") . '\', $_smarty_tpl); ? >';
        }
         return $_output;
    } 
} 

?>
