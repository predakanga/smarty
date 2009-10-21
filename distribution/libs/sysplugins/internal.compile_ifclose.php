<?php
/**
* Smarty Internal Plugin Compile If Close
* 
* Compiles the {/if} tag
* 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews 
*/
             
/**
* Smarty Internal Plugin Compile IfClose Class
*/
class Smarty_Internal_Compile_IfClose extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {/if} tag
    * 
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler; 
        list($nesting, $compiler->tag_nocache) = $this->_close_tag(array('if', 'else', 'elseif'));
        $tmp = '';
        for ($i = 0; $i < $nesting ; $i++) $tmp .= '}';
        return "<?php $tmp?>";
    } 
} 

?>                    
