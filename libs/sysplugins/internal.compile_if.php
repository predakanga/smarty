<?php
/**
* Smarty Internal Plugin Compile If
* 
* Compiles the {if} tag
* 
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews 
*/
/**
* Smarty Internal Plugin Compile If Class
*/
class Smarty_Internal_Compile_If extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {if} tag
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    function compile($args)
    {
        $this->_open_tag('if');
        return '<?php if (' . $args[ifexp] . '): ?>';
    } 
} 

/**
* Smarty Internal Plugin Compile Else Class
*/
class Smarty_Internal_Compile_Else extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {else} tag
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    function compile($args)
    {
        $this->_close_tag(array('if', 'elseif'));
        $this->_open_tag('else');

        return '<?php else: ?>';
    } 
} 
/**
* Smarty Internal Plugin Compile ElseIf Class
*/
class Smarty_Internal_Compile_ElseIf extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {elseif} tag
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    function compile($args)
    {
        $this->_close_tag(array('if', 'elseif'));
        $this->_open_tag('elseif');

        return '<?php elseif (' . $args[ifexp] . '): ?>';
    } 
} 

/**
* Smarty Internal Plugin Compile IfClose Class
*/
class Smarty_Internal_Compile_IfClose extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {/if} tag
    * 
    * @param array $args array with attributes from parser
    * @return string compiled code
    */
    public function compile($args)
    {
        $this->_close_tag(array('if', 'else', 'elseif'));
        return "<?php endif;?>";
    } 
} 

?>
