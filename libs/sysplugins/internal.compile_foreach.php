<?php 
// Compiler foreach tags
// Not yet complete
// - name tag not yet supported
// - nesting checks missing
class Smarty_Internal_Compile_Foreach extends Smarty_Internal_CompileBase {
    /**
    * Compile {foreach ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
        $this->required_attributes = array('from', 'item');
        $this->optional_attributes = array('name', 'key'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_open_tag('foreach');

        $from = $_attr['from'];
        $item = $_attr['item'];

        if (isset($_attr['key'])) {
            $key = $_attr['key'];
            $key_part = "\$_smarty_tpl->tpl_vars->tpl_vars[$key]->value => ";
        } else {
            $key = null;
            $key_part = '';
        } 

        if (isset($_attr['name'])) {
            $name = $_attr['name'];
        } else {
            $name = null;
        } 
        $output = "<?php ";
        $output .= " \$_smarty_tpl->tpl_vars->tpl_vars[$item] = new Smarty_Variable;\n";
        if ($key != null) {
            $output .= " \$_smarty_tpl->tpl_vars->tpl_vars[$key] = new Smarty_Variable;\n";
        } 
        $output .= " \$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array');}\n";
        if ($name != null) {
            $output .= " \$_smarty_tpl->tpl_vars->tpl_vars['smarty']->value['foreach'][$name]['total'] = count(\$_from);\n";
            $output .= " \$_smarty_tpl->tpl_vars->tpl_vars['smarty']->value['foreach'][$name]['iteration']=0;\n";
        } 
        $output .= "if (count(\$_from) > 0){\n";
        $output .= "    foreach (\$_from as " . $key_part . "\$_smarty_tpl->tpl_vars->tpl_vars[$item]->value){\n";
        if ($name != null) {
            $output .= " \$_smarty_tpl->tpl_vars->tpl_vars['smarty']->value['foreach'][$name]['iteration']++;\n";
        } 
        $output .= "?>";

        return $output;
    } 
} 
class Smarty_Internal_Compile_Foreachelse extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {foreachelse} tag
        * 
        * @return string 
        */ 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_close_tag('foreach');
        $this->_open_tag('foreachelse');
        return "<?php }} else { ?>";
    } 
} 
class Smarty_Internal_Compile_End_Foreach extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {/foreach} tag
        * 
        * @return string 
        */ 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $_open_tag = $this->_close_tag(array('foreach', 'foreachelse'));
        if ($_open_tag == 'foreachelse')
            return "<?php } ?>";
        else
            return "<?php }} ?>";
    } 
}

    ?>
