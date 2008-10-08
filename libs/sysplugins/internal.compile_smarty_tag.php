<?php 
// The lexer/parser calls this for any tags which will require compilation.
// Exeption are varibale tags. For this the lexer/parser does generate the code directly
class Smarty_Internal_Compile_Smarty_Tag extends Smarty_Internal_CompileBase {

    public function execute($args)
    { 
      static $objects = array();

        // $args contains the attributes parsed and compiled by the lexer/parser
        // get type of smarty tag
        $tag = $args['_smarty_tag'];
        unset($args['_smarty_tag']); 

        // Load required compiler module
        // Build class name
        $class_name = "Smarty_Internal_Compile_{$tag}"; 

        // Check if there is already an instace for that tag
        if (!is_object($objects[$tag]) && class_exists($class_name)) {
            // Now load plugin if required and create instance
            $this->smarty->loadPlugin($class_name);
            $objects[$tag] = new $class_name;
        } 

        if (is_object($objects[$tag])) {
            // compile the smarty tag
            $output = $objects[$tag]->compile($args);

            if ($this->compiler->_compiler_status->nocache && $this->smarty->caching && $this->smarty->cache_lifetime != 0 && $output != '') {
                // If we have a ncocache section and caching enabled make the compiled template to inject the compiled code into the cache file
                $output = str_replace("'","\'",$output);
                $output = "<?php echo '$output';?>";
            } 
            // just for debugging
            if ($this->smarty->internal_debugging) {
                // echo "<br>compiled tag '".$output."'<br>";
            } 

            return $output;

        } else {
            $this->smarty->trigger_template_error ("missing compiler module for tag \"" . $tag . "\"");
        } 
    } 
} 

?>
