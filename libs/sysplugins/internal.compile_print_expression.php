<?php 
/**
* Smarty Internal Plugin Compile Print Expression
*
* Compiles any tag which will output an expression or variable
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/
/**
* Smarty Internal Plugin Compile Print Expression Class
*/ 
class Smarty_Internal_Compile_Print_Expression extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for gererting output from any expression
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
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
