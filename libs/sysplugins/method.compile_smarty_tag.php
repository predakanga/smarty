<?php

// The lexer/parser calls this for any tags which will require compilation. 
// Exeption are varibale tags. For this the lexer/parser does generate the code directly

class Smarty_Method_Compile_Smarty_Tag extends Smarty_Internal_CompileBase {
    public function execute($args)
    {
        // $args include the attributes parsed and compiled by the lexer/parser
        
        // get type of smarty tag
        $tag = $args[0]['_smarty_tag'];
        unset($args[0]['_smarty_tag']); 

        // Load required compiler module
        // Build class name
        $class_name = "Smarty_Internal_Compile_{$tag}";
        
        // Check if there is already an instace for that tag
        if (!is_object($this->compiler->_compiler_class[$tag]) and class_exists($class_name)) {
            // No load plugin if required and create instance 
            $this->compiler->_compiler_class[$tag] = new $class_name;
        } 
        if (is_object($this->compiler->_compiler_class[$tag])) {
            // compile the smarty tag
            return $this->compiler->_compiler_class[$tag]->compile($args[0]);
        } else {
            echo "missing compiler module " . $tag . "<br>";
        } 
    } 
} 

?>
