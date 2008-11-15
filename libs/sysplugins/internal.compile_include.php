<?php

/**
* Smarty Internal Plugin Compile Include
*
* Compiles the {include} tag 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Compile Include Class
*/ 
class Smarty_Internal_Compile_Include extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {include} tag
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    public function compile($args)
    { 
        $this->required_attributes = array('file');
        $this->optional_attributes = array('_any'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args); 
        // save posible attributes
        $include_file = $_attr['file'];
        if (isset($_attr['assign'])) {
              // output will be stored in a smarty variable instead of beind displayed
            $_assign = $_attr['assign'];
        }
  
        /*
        * if the {include} tag provides individual parameter for caching
        * it will not be included into the common cache file and treated like
        * a nocache section
        */       
        if (isset($_attr['caching_lifetime'])) {
            $_caching_lifetime = $_attr['caching_lifetime'];
            $this->compiler->_compiler_status->tag_nocache = true;
        } 
        if ($_attr['nocache'] == 'true') {
            $_caching = 'false';
            $this->compiler->_compiler_status->tag_nocache = true;
        } 
        if ($_attr['caching'] == 'true') {
            $_caching = 'true';
        } 
        // create template object
        $_output = "\$_template = new Smarty_Template ($include_file, \$_smarty_tpl->tpl_vars);"; 
        // delete {include} standard attributes
        unset($_attr['file'], $_attr['assign'], $_attr['caching_lifetime'], $_attr['nocache'], $_attr['caching']); 
        // remaining attributes must be assigned as smarty variable
        if (isset($_attr)) {
            // create variables
            foreach ($_attr as $_key => $_value) {
                $_output .= "\$_template->assign('$_key',$_value);";
            } 
        } 
        // add caching parameter if required
        if (isset($_caching_lifetime)) {
            $_output .= "\$_template->caching_lifetime = $_caching_lifetime;";
        } 
        if (isset($_caching)) {
            $_output .= "\$_template->caching = $_caching;";
        } elseif (isset($_caching_lifetime)) {
            $_output .= "\$_template->caching = true;";
        }
        //was there an assign attribute 
        if (isset($_assign)) {
            $_output .= "\$_tmp = \$_smarty_tpl->smarty->fetch(\$_template);";
        } else {
            $_output .= "echo \$_smarty_tpl->smarty->fetch(\$_template);";
        } 

        if (isset($_assign)) {
            $_output .= "\$_smarty_tpl->tpl->assign($_assign,\$_tmp);  unset(\$_tmp);";
            // create variable for compiler
//            $this->compiler->template->tpl_vars->tpl_vars[$_var] = new Smarty_variable(null, $_nocache_boolean, $_global_boolean);
        } 
        return "<?php $_output ?>";
    } 
} 

?>
