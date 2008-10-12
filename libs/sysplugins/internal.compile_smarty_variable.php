<?php 
// Generate code for variable display
class Smarty_Internal_Compile_Smarty_Variable extends Smarty_Internal_CompileBase {
    public function execute($args)
    { 
        // $args contains Smarty variable
        $output = "echo " . $args['var'] . ";";
        if ($this->compiler->_compiler_status->nocache || $args['caching'] == false) {
            // If we have a ncocache section and caching enabled make the compiled template to inject the compiled code into the cache file
                $output = str_replace("'", "\'", $output);
                $output = "<?php \$_tmp = '$output'; if (\$this->smarty->caching) echo '<?php '.\$_tmp.'?>'; else eval(\$_tmp);\n?>";
        } else {
             $output = "<?php ".$output."?>";
        }
        // just for debugging
        if ($this->smarty->internal_debugging) {
            // echo "<br>compiled tag '".$output."'<br>";
        } 

        return $output;
    } 
} 

?>
