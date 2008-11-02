<?php 
// Compiler for capture tags
class Smarty_Internal_Compile_Capture extends Smarty_Internal_CompileBase {
    /**
    * Compile {capture ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
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
class Smarty_Internal_Compile_End_Capture extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {/capture} tag
        * 
        * @return string 
        */ 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_close_tag(array('capture'));

        list($buffer, $assign) = array_pop($this->smarty->_capture_stack);

//        $_output = "<?php \$_smarty_tpl->smarty->_smarty_vars['capture'][$buffer] = ob_get_contents();";
        $_output = "<?php ";
        if (isset($assign)) {
            $_output .= " \$_smarty_tpl->assign($assign, ob_get_contents());";
        } 
        $_output .= " ob_clean(); ?>";
        return $_output;
    } 
} 

?>
