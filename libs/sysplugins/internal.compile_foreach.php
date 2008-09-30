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
            // if (!preg_match('~^\w+$~', $key)) {
            // return $smarty->_syntax_error("foreach: 'key' must to be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
            // }
            $key_part = "\$this->smarty->tpl_vars['$key'] => ";
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
        $output .= "\$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array'); }";
        if (isset($name)) {
            $foreach_props = "\$this->_foreach[$name]";
            $output .= "{$foreach_props} = array('total' => count(\$_from), 'iteration' => 0);\n";
            $output .= "if ({$foreach_props}['total'] > 0):\n";
            $output .= "    foreach (\$_from as $key_part\$this->smarty->tpl_vars[$item]):\n";
            $output .= "        {$foreach_props}['iteration']++;\n";
        } else {
            $output .= "if (count(\$_from)):\n";
            $output .= "    foreach (\$_from as $key_part\$this->smarty->tpl_vars[$item]):\n";
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
        return "<?php endforeach; else: ?>";
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
            return "<?php endif; unset(\$_from); ?>";
        else
            return "<?php endforeach; endif; unset(\$_from); ?>";
    } 
} 

?>
