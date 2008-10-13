<?php 
// The lexer/parser calls this for any tags which will require compilation.
// Exeption are varibale tags. For this the lexer/parser does generate the code directly
class Smarty_Internal_Compile_Smarty_Tag extends Smarty_Internal_CompileBase {
    public function execute($args)
    {
        static $tag_compiler_objects = array(); 
        // $args contains the attributes parsed and compiled by the lexer/parser
        // get type of smarty tag
        $tag = $args['_smarty_tag'];
        unset($args['_smarty_tag']); 
        // Load required compiler module
        // Build class name
        $class_name = "Smarty_Internal_Compile_{$tag}"; 
        // Check if there is already an instace for that tag
        if (!is_object($tag_compiler_objects[$tag])) {
            // Now load plugin if required and create instance
            $this->smarty->loadPlugin($class_name);
            if (class_exists($class_name)) {
                $tag_compiler_objects[$tag] = new $class_name;
            } 
        } 

        if (is_object($tag_compiler_objects[$tag])) {
            // compile the smarty tag
            $output = $tag_compiler_objects[$tag]->compile($args);
     
            // If the template is not evaluated and we have a ncocache section and or a nocache tag
            // make the compiled template to inject the compiled code into the cache file
            if (!$this->compiler->template->isEvaluated() && $output != '' &&
               ($this->compiler->_compiler_status->nocache || $this->compiler->_compiler_status->tag_nocache)) {            
                $output = str_replace("'", "\'", $output);
                $output = "<?php \$_tmp = '$output'; if (\$this->smarty->caching) echo \$_tmp; else eval(\$_tmp);\n?>";
            } 
            $this->compiler->_compiler_status->tag_nocache = false; 
            // just for debugging
            if ($this->smarty->internal_debugging) {
                echo "<br>compiled tag '".$output."'<br>";
            } 

            return $output;
        } else {
            $this->compiler->trigger_template_error ("missing compiler module for tag \"" . $tag . "\"");
        } 
    } 
} 

?>
