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
            $key_part = "\$_key => ";
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
        $output .= " \$this->tpl_vars->tpl_vars[$item] = new Smarty_Variable;\n";
        $output .= " \$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array');}\n";
        $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['total']=count(\$_from);\n";
        $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['iteration']=0;\n";
        $output .= "if (\$this->tpl_vars->tpl_vars[$item]->prop['total'] > 0):\n";
        $output .= "    foreach (\$_from as \$this->tpl_vars->tpl_vars[$item]->prop['key'] => \$this->tpl_vars->tpl_vars[$item]->value):\n";
        $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['iteration']++;\n";
//            $output .= "        {$foreach_props}['iteration']++;\n";
//         if (false && isset($name)) {
//            $foreach_props = "\$this->tpl_vars->tpl_vars['smarty']->data['foreach'][$name]";
//            $output .= "{$foreach_props} = array('total' => count(\$_from), 'iteration' => 0);\n";
//            $output .= "if ({$foreach_props}['total'] > 0):\n";
//            $output .= "    foreach (\$_from as $key_part\$this->tpl_vars->tpl_vars[$item]->data):\n";
//            $output .= "        {$foreach_props}['iteration']++;\n";
//        } else {
//            $output .= "if (count(\$_from)):\n";
//            $output .= "    foreach (\$_from as $key_part\$_item):\n";
//            if ($key !== null) {
//                $output .= " \$this->tpl_vars->assign($key,\$_key);";
//            } 
//            $output .= " \$this->tpl_vars->assign($item,\$_item);";
//        } 
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
class Smarty_Internal_Compile_End_For extends Smarty_Internal_CompileBase {
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
