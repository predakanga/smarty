<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// Compile include tag
// Not yet working completely
// The idea is just to call $smarty->fetchtoget teh work done
class Smarty_Internal_Compile_Include extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->required_attributes = array('file');
        $this->optional_attributes = array('_any'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $include_file = str_replace("'", "", $_attr['file']);

        if (isset($_attr['assign'])) {
            $_assign = $_attr['assign'];
        } 

        unset($_attr['file']);
        unset($_attr['assign']); 

        // save template vars
        $_output = "\$_smarty_tpl_vars = \$_smarty->tpl_vars; ";

        foreach ($_attr as $_key => $_value) {
            $_output .= "\$_smarty->assign('$_key','$_value'); ";
        } 

        if (isset($_assign)) {
            $_output .= "\$_tmp = \$_smarty->fetch('$include_file'); ";
        } else {
            $_output .= "echo \$_smarty->fetch('$include_file'); ";
        } 
        // restore template vars
        $_output .= "\$_smarty->tpl_vars = \$_smarty_tpl_vars; unset(\$_smarty_tpl_vars);";

        if (isset($_assign)) {
            $_output .= "\$_smarty->>assign('$_assign',\$_tmp);  unset(\$_tmp);";
        } 
        return "<?php $_output ?>";
    } 
} 

?>
