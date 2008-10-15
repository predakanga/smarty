<?php 
// Generate code for variable display
class Smarty_Internal_Compile_Print_Expression extends Smarty_Internal_CompileBase {
    public function compile($args)
    { 
        // This tag does create output
        $this->has_output = true;

        $this->required_attributes = array('value');
        
         // check and get attributes
        $_attr = $this->_get_attributes($args);

        // display value
        $output = '<?php echo ' . $_attr['value'] . ';?>';

        return $output;
    } 
} 

?>
