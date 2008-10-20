<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// compiles the {assign } tag to php
class Smarty_Internal_Compile_Assign extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->required_attributes = array('var', 'value');
        $this->optional_attributes = array('nocache', 'global'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $_nocache = 'null';
        $_global = 'null';
        $_nocache_boolean = null;
        $_global_boolean = null;
        $_var = trim($_attr['var'],"'");

        if ($_attr['nocache'] == 'true') {
            $_nocache = 'true';
            $_nocache_boolean = true;
        } 
        if ($_attr['global'] == 'true') {
            $_global = 'true';
            $_global_boolean = true;
        } 

/*        if (isset($this->compiler->template->tpl_vars->tpl_vars[$_var])) {
            // remember mark nocache for the compiler
            if ($_nocache_boolean === true) $this->compiler->template->tpl_vars[$_var]->nocache = true;
        } else {
            // create variable for compiler
            $this->compiler->template->tpl_vars->tpl_vars[$_var] = new Smarty_variable(null, $_nocache_boolean, $_global_boolean);
        }   */
        return "<?php \$this->assign($_attr[var],$_attr[value],$_nocache,$_global);?>";
    } 
} 

?>
