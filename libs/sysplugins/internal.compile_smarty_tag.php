<?php 
// The lexer/parser calls this for any tags which will require compilation.
// 
class Smarty_Internal_Compile_Smarty_Tag extends Smarty_Internal_CompileBase {
    public function execute($args)
    { 
        // $args contains the attributes parsed and compiled by the lexer/parser
        // get type of smarty tag
        $tag = $args['_smarty_tag'];
        $nocache = false;
        if (isset($args['_smarty_nocache'])) {
            $nocache = $args['_smarty_nocache'];
        } 
        unset($args['_smarty_tag'], $args['_smarty_nocache']); 
        // compile the smarty tag
        if (!($output = $this->smarty->compile->$tag($args)) === false) {
            // Does it create output?
            if (Smarty_Internal_Compile::$objects[$tag]->has_output) {
                $output .= "\n";
            } 
            // If the template is not evaluated and we have a ncocache section and or a nocache tag
            // make the compiled template to inject the compiled code into the cache file
            if (false && !$this->compiler->template->isEvaluated() && $output != '' &&
                    ($this->compiler->_compiler_status->nocache || $nocache)) {
                $output = str_replace("'", "\'", $output);
                $output = "<?php \$_tmp = '$output'; if (\$this->smarty->caching) echo \$_tmp; else eval(\$_tmp);\n?>";
            } 
            $this->compiler->_compiler_status->tag_nocache = false; 
            // just for debugging
            if ($this->smarty->internal_debugging) {
                echo "<br>compiled tag '" . htmlentities($output) . "'<br>";
            } 

            return $output;
        } else {
            $this->compiler->trigger_template_error ("missing compiler module for tag \"" . $tag . "\"");
        } 
    } 
} 

?>
