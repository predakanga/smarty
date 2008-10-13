<?php 
// Generate code for variable display
class Smarty_Internal_Compile_Smarty_Variable extends Smarty_Internal_CompileBase {
    public function execute($args)
    { 
        // $args contains Smarty variable
        $output = "echo " . $args['var'] . ";";

       // If the template is not evaluated and we have a ncocache section and or a nocache expression
       // make the compiled template to inject the compiled code into the cache file
       if (!$this->compiler->template->isEvaluated() &&
            ($this->compiler->_compiler_status->nocache || $args['caching'] == false)) {
                $output = str_replace("'", "\'", $output);
                $output = "<?php \$_tmp = '$output'; if (\$this->smarty->caching) echo '<?php '.\$_tmp.'?>'; else eval(\$_tmp);\n?>";
        } else {
             $output = "<?php ".$output."?>";
        }
        // just for debugging
        if ($this->smarty->internal_debugging) {
             echo "<br>compiled tag '".$output."'<br>";
        } 

        return $output;
    } 
} 

?>
