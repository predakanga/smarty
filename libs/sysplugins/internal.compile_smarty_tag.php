<?php 
// The lexer/parser calls this for any tags which will require compilation.
// 
class Smarty_Internal_Compile_Smarty_Tag extends Smarty_Internal_CompileBase {
    public function execute($args)
    { 
        // $args contains the attributes parsed and compiled by the lexer/parser
        // get type of smarty tag
        $_tag = $args['_smarty_tag'];
        $_nocache = false;
        if ($args['_smarty_nocache'] == true) {
            $_nocache = $args['_smarty_nocache'];
        } 
        // remove internal parameter 
        unset($args['_smarty_tag'], $args['_smarty_nocache']); 
        // compile the smarty tag
        if (!($_output = $this->smarty->compile->$_tag($args)) === false) {
            // Does it create output?
            if (Smarty_Internal_Compile::$objects[$_tag]->has_output) {
                $_output .= "\n";
            } 
            // If the template is not evaluated and we have a ncocache section and or a nocache tag
            // make the compiled template to inject the compiled code into the cache file
            if (!$this->compiler->template->isEvaluated() && $_output != '' &&
                ($this->compiler->_compiler_status->nocache | $this->compiler->_compiler_status->tag_nocache | $this->compiler->_compiler_status->nocache || $_nocache)) {
                $_output = str_replace("'", "\'", $_output);
                $_output = "<?php \$_tmp = '$_output'; if (\$this->smarty->caching) echo \$_tmp; else eval(\$_tmp);\n?>";
            } 
            $this->compiler->_compiler_status->tag_nocache = false; 
            // just for debugging
            if ($this->smarty->internal_debugging) {
                echo "<br>compiled tag '" . htmlentities($_output) . "'<br>";
            } 

            return $_output;
        } else {
            $this->compiler->trigger_template_error ("missing compiler module for tag \"" . $_tag . "\"");
        } 
    } 
} 

?>
