<?php 
// Compiler for tags

// Not yet complete
// - name tag not yet supported
// - nesting checks missing
class Smarty_Internal_Compile_For extends Smarty_Internal_CompileBase {
    /**
    * Compile {for ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
        if (isset($args['from'])) {
            //  {for $var in $array}  style
            $this->required_attributes = array('from', 'item');
            // check and get attributes
            $_attr = $this->_get_attributes($args);

            $this->_open_tag('for');

            $from = $_attr['from'];
            $item = $_attr['item'];

            $output = "<?php ";
            $output .= " \$this->tpl_vars->tpl_vars[$item] = new Smarty_Variable;\n";
            $output .= " \$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array');}\n";
            $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['total']=count(\$_from);\n";
            $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['iteration']=0;\n";
            $output .= "if (\$this->tpl_vars->tpl_vars[$item]->prop['total'] > 0){\n";
            $output .= "    foreach (\$_from as \$this->tpl_vars->tpl_vars[$item]->prop['key'] => \$this->tpl_vars->tpl_vars[$item]->value){\n";
            $output .= " \$this->tpl_vars->tpl_vars[$item]->prop['iteration']++;\n";
            $output .= "?>";

            return $output;
        } else {
            $this->required_attributes = array('var','ifexp', 'start', 'loop'); 
            // check and get attributes
            $_attr = $this->_get_attributes($args);

            $this->_open_tag('for');

            $output = "<?php ";
            $output .= " \$this->tpl_vars->tpl_vars[$_attr[var]] = new Smarty_Variable;\n";
            $output .= " \$this->tpl_vars->tpl_vars[$_attr[var]]->value = $_attr[start]; if ($_attr[ifexp]) { for (\$this->tpl_vars->tpl_vars[$_attr[var]]->value = $_attr[start];$_attr[ifexp];\$this->tpl_vars->tpl_vars[$_attr[var]]->value$_attr[loop]) {";
            $output .= "?>";

            return $output;
        } 
    } 
} 
class Smarty_Internal_Compile_Forelse extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {forelse} tag
        * 
        * @return string 
        */ 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_close_tag('for');
        $this->_open_tag('forelse');
        return "<?php }} else { ?>";
    } 
} 
class Smarty_Internal_Compile_End_For extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {/for} tag
        * 
        * @return string 
        */ 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $_open_tag = $this->_close_tag(array('for', 'forelse'));
        if ($_open_tag == 'forelse')
            return "<?php } ?>";
        else
            return "<?php }} ?>";
    } 
} 

?>
