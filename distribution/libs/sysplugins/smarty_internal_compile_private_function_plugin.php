<?php
/**
 * Smarty Internal Plugin Compile Function Plugin
 * 
 * Compiles code for the execution of function plugin
 * 
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews 
 */

/**
 * Smarty Internal Plugin Compile Function Plugin Class
 */
class Smarty_Internal_Compile_Private_Function_Plugin extends Smarty_Internal_CompileBase {
	// attribute definitions
    public $required_attributes = array();
    public $optional_attributes = array('_any'); 

    /**
     * Compiles code for the execution of function plugin
     * 
     * @param array $args array with attributes from parser
     * @param object $compiler compiler object
     * @param array $parameter array with compilation parameter
     * @param string $tag name of function plugin
     * @param string $function PHP function name
     * @return string compiled code
     */
    public function compile($args, $compiler, $parameter, $tag, $function)
    {
        $this->compiler = $compiler; 
        // This tag does create output
        $this->compiler->has_output = true;
        
        // is plugin a class?
        $this->compiler->smarty->loadPlugin($function);
        if ($_class = class_exists($function,false)){
            $_nocache = property_exists($function,'nocache') && $function::$nocache;
            $_cache_attr = property_exists($function,'cache_attr') ? $function::$cache_attr : array();
        } else {
            $_nocache = false;
            $_cache_attr = array();
        }

        // check and get attributes
        $_attr = $this->_get_attributes($args); 
        if ($_attr['nocache'] === true || $_nocache) {
            $this->compiler->tag_nocache = true;
        }
        unset($_attr['nocache']);
        // convert attributes into parameter array string
        $_paramsArray = array();
        foreach ($_attr as $_key => $_value) {
            if (is_int($_key)) {
                $_paramsArray[] = "$_key=>$_value";
            } elseif ($this->compiler->template->caching && $this->compiler->tag_nocache && in_array($_key,$_cache_attr)) {
				$_value = str_replace("'","^#^",$_value);
                $_paramsArray[] = "'$_key'=>^#^.var_export($_value,true).^#^";
            } else {
                $_paramsArray[] = "'$_key'=>$_value";
            } 
        } 
        $_params = 'array(' . implode(",", $_paramsArray) . ')'; 
        if ($_class) {
            $function .= '::run';
        }
        // compile code
        $output = "<?php echo {$function}({$_params},\$_smarty_tpl);?>\n";
        return $output;
    } 
} 

?>