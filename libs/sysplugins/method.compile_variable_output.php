<?php 
// Generate code for variable display
class Smarty_Method_Compile_Variable_output extends Smarty_Internal_CompileBase {
    public function execute($args)
    { 
        // $args contains Smarty variable
        $output = "<?php echo str_replace('\"','&quot;'," . $args[0] . ");?>";

        if ($this->compiler->_compiler_status->nocache && $this->smarty->caching && $this->smarty->cache_lifetime != 0) {
            // If we have a ncocache section and caching enabled make the compiled template to inject the compiled code into the cache file
            $output = str_replace("'","\'",$output);
            $output = "<?php echo '$output';?>\n";
        } 
        // just for debugging
        if ($this->smarty->internal_debugging) {
            // echo "<br>compiled tag '".$output."'<br>";
        } 

        return $output;
    } 
} 

?>
