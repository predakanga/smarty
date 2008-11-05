<?php 
/**
* Smarty Internal Plugin Compile Print Expression
*
* Compiles any tag which will output an expression or variable
* @package Smarty
* @subpackage compiler
* @author Uwe Tews
*/
class Smarty_Internal_Compile_Print_Expression extends Smarty_Internal_CompileBase {
    public function compile($args)
    { 
        // This tag does create output
        $this->compiler->has_output = true;

        $this->required_attributes = array('value');
        $this->optional_attributes = array('nocache');
        
         // check and get attributes
        $_attr = $this->_get_attributes($args);
       if ($_attr['nocache'] === 'true') {
                 $this->compiler->_compiler_status->tag_nocache = true;
                 unset($args['nocache']); 
        }

        // display value
        $output = '<?php echo ' . $_attr['value'] . ';?>';

        return $output;
    } 
} 

?>
