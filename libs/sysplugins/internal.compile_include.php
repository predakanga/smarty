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
        // for now do not {include} in the cache file
        // $this->compiler->_compiler_status->tag_nocache = true;
        $this->required_attributes = array('file');
        $this->optional_attributes = array('_any'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $include_file = $_attr['file'];

        if (isset($_attr['assign'])) {
            $_assign = $_attr['assign'];
        } 

        if (isset($_attr['caching_lifetime'])) {
            $_caching_lifetime = $_attr['caching_lifetime'];
        } 

        if ($_attr['nocache'] == 'true') {
            $_caching = false;
        } 

        unset($_attr['file'], $_attr['assign'], $_attr['caching_lifetime'], $_attr['nocache']);

        $_output = '';

        foreach ($_attr as $_key => $_value) {
            $_output .= "\$this->assign('$_key',$_value);";
        } 

        if (isset($_caching_lifetime) || isset($_caching)) {
            $_output .= " \$_template = new Smarty_Template ($include_file, \$this->tpl_vars);";

            if (isset($_caching_lifetime)) {
                $_output .= "\$_template->caching_lifetime = $_caching_lifetime;";
            } 
            if (isset($_caching)) {
                $_output .= "\$_template->caching = false;";
            } 
            if (isset($_assign)) {
                $_output .= "\$_tmp = \$this->smarty->fetch(\$_template);";
            } else {
                $_output .= "echo \$this->smarty->fetch(\$_template);";
            } 
        } else {
            if (isset($_assign)) {
                $_output .= "\$_tmp = \$this->smarty->fetch($include_file, \$this->tpl_vars);";
            } else {
                $_output .= "echo \$this->smarty->fetch($include_file, \$this->tpl_vars);";
            } 
        } 

        if (isset($_assign)) {
            $_output .= "\$this->smarty->assign($_assign,\$_tmp);  unset(\$_tmp);";
        } 
        return "<?php $_output ?>";
    } 
} 

?>
