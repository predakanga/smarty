<?php
/**
 * Smarty Internal Plugin Compile Print Expression
 *
 * Compiles any tag which will output an expression or variable
 *
 * @package Smarty
 * @subpackage Compiler
 * @author Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile Print Expression Class
 */
class Smarty_Internal_Compile_Private_Print_Expression extends Smarty_Internal_CompileBase {
	// attribute definitions
    public $optional_attributes = array('assign');
    public $option_flags = array('nocache', 'nofilter');

    /**
     * Compiles code for gererting output from any expression
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
        // nocache option
        if ($_attr['nocache'] === true) {
            $compiler->tag_nocache = true;
        }
        // filter handling
        if ($_attr['nofilter'] === true) {
            $_filter = 'false';
        } else {
            $_filter = 'true';
        }
        // compiled output
        // compiled output
        if (isset($_attr['assign'])) {
            // assign output to variable
            $output = "<?php \$_smarty_tpl->assign({$_attr['assign']},{$parameter['value']});?>";
        } else {
            // display value
            $output = $parameter['value'];
            if (!$_attr['nofilter'] && (isset($compiler->smarty->registered_filters[Smarty::FILTER_VARIABLE]) || isset($compiler->smarty->autoload_filters[Smarty::FILTER_VARIABLE]))) {
                $output = $compiler->compileTag('private_filter', array(), array('value' => $output));
            } else {
                $output = $parameter['value'];
            }
            // default modifier
            if (!$_attr['nofilter'] && !empty($compiler->smarty->default_modifiers)) {
                if (empty($compiler->default_modifier_list)) {
                    $modifierlist = array();
                    foreach ($compiler->smarty->default_modifiers as $key => $single_default_modifier) {
                        preg_match_all('/(\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'|"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|:|[^:]+)/', $single_default_modifier, $mod_array);
                        for ($i = 0, $count = count($mod_array[0]);$i < $count;$i++) {
                            if ($mod_array[0][$i] != ':') {
                                $modifierlist[$key][] = $mod_array[0][$i];
                            }
                        }
                    }
                    $compiler->default_modifier_list  = $modifierlist;
                }
                $output = $compiler->compileTag('private_modifier', array(), array('modifierlist' => $compiler->default_modifier_list, 'value' => $output));
            }
            // tag modifier
            if (!empty($parameter['modifierlist'])) {
                $output = $compiler->compileTag('private_modifier', array(), array('modifierlist' => $parameter['modifierlist'], 'value' => $output));
            }
            $compiler->has_output = true;
            $output = "<?php echo {$output};?>";
        }
        return $output;
    }
}

?>