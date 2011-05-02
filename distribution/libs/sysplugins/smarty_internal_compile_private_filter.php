<?php
/**
* Smarty Internal Plugin Compile Variable Filter
*
* Compiles code for variable filter
*
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/

/**
* Smarty Internal Plugin Compile Filter Class
*/
class Smarty_Internal_Compile_Private_Filter extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for variable filter execution
    *
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @param array $parameter array with compilation parameter
    * @return string compiled code
    */
    public function compile($args, $compiler, $parameter)
    {
        // check and get attributes
        $_attr = $this->_get_attributes($compiler, $args);
        $output = $parameter['value'];
        if (!empty($compiler->template->smarty->autoload_filters[Smarty::FILTER_VARIABLE])) {
            foreach ((array)$compiler->template->smarty->autoload_filters[Smarty::FILTER_VARIABLE] as $name) {
                $plugin_name = "smarty_variablefilter_{$name}";
                if ($path = $compiler->smarty->loadPlugin($plugin_name, false)) {
                    if ($compiler->template->caching) {
                        $compiler->template->required_plugins['nocache'][$modifier][Smarty::FILTER_VARIABLE]['file'] = $path;
                        $compiler->template->required_plugins['nocache'][$modifier][Smarty::FILTER_VARIABLE]['function'] = $plugin_name;
                    } else {
                        $compiler->template->required_plugins['compiled'][$modifier][Smarty::FILTER_VARIABLE]['file'] = $path;
                        $compiler->template->required_plugins['compiled'][$modifier][Smarty::FILTER_VARIABLE]['function'] = $plugin_name;
                    }
                } else {
                    // nothing found, throw exception
                    throw new SmartyException("Unable to load filter {$plugin_name}");
                }
                $output = "{$plugin_name}({$output})";
            }
        }
        // loop over registerd filters of specified type
        if (!empty($compiler->template->smarty->registered_filters[Smarty::FILTER_VARIABLE])) {
            foreach ($compiler->template->smarty->registered_filters[Smarty::FILTER_VARIABLE] as $key => $function) {
                if (!is_array($function)) {
                    $output = "{$function}({$output},\$_smarty_tpl->smarty)";
                } else if (is_object($function[0])) {
                    $output = "\$_smarty_tpl->smarty->registered_filters[Smarty::FILTER_VARIABLE][{$key}][0]->{$function[1]}({$output},\$_smarty_tpl->smarty)";
                } else {
                    $output = "{$function[0]}::{$function[1]}({$output},\$_smarty_tpl->smarty)";
                }
            }
        }
        return $output;
    }
}
?>