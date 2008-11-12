<?php
/**
* Smarty Internal Plugin Compile Function Plugin
* 
* Compiles code for the execution of function plugin
* 
* @package Smarty
* @subpackage compiler
* @author Uwe Tews 
*/
class Smarty_Internal_Compile_Function_Plugin extends Smarty_Internal_CompileBase {
    public function compile($args, $tag)
    { 
        // This tag does create output
        $this->compiler->has_output = true;

        $this->required_attributes = array();
        $this->optional_attributes = array('_any'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);
        if ($_attr['nocache'] === 'true') {
            $this->compiler->_compiler_status->tag_nocache = true;
            unset($args['nocache']);
        }
        // convert attributes into parameter array string 
        $_paramsArray = array();
        foreach ($_attr as $_key => $_value) {
            $_paramsArray[] = "'$_key'=>$_value";
        } 
        $_params = 'array(' . implode(",", $_paramsArray) . ')'; 
        // compile code
        $output = '<?php echo $_smarty_tpl->smarty->function->' . $tag . '(' . $_params . ',$_smarty_tpl->smarty);?>';

        return $output;
    } 
} 

?>
