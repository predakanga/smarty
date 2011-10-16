<?php

/**
* Smarty Internal Plugin Compile Modifier
*
* Compiles code for modifier execution
*
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/

/**
* Smarty Internal Plugin Compile Modifier Class
*
* @package Smarty
* @subpackage Compiler
*/
class Smarty_Internal_Compile_Private_Modifier extends Smarty_Internal_CompileBase {

    /**
    * Compiles code for modifier execution
    *
    * @param array  $args      array with attributes from parser
    * @param object $compiler  compiler object
    * @param array  $parameter array with compilation parameter
    * @return string compiled code
    */
    public function compile($args, $compiler, $parameter)
    {
        // check and get attributes
        $_attr = $this->getAttributes($compiler, $args);
        $output = $parameter['value'];
        // loop over list of modifiers
        foreach ($parameter['modifierlist'] as $single_modifier) {
            $modifier = $single_modifier[0];
            $single_modifier[0] = $output;
            $params = implode(',', $single_modifier);
            // check for registered modifier
            if (isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier])) {
                $function = $compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier][0];
                $object = $this->testParameter($function, $single_modifier, $compiler);
                if (!is_array($function)) {
                    $output = "{$function}({$object}{$params})";
                } else {
                    if (is_object($function[0])) {
                        $output = '$_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][\'' . $modifier . '\'][0][0]->' . $function[1] . '(' . $object . $params . ')';
                    } else {
                        $output = $function[0] . '::' . $function[1] . '(' . $object . $params . ')';
                    }
                }
            } else if (isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0])) {
                $output = call_user_func($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0], $single_modifier, $compiler->smarty);
                // check for plugin modifiercompiler
            } else if ($compiler->smarty->loadPlugin('smarty_modifiercompiler_' . $modifier)) {
                // check if modifier allowed
                if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedModifier($modifier, $compiler)) {
                    $plugin = 'smarty_modifiercompiler_' . $modifier;
                    $object = null;
                    if ($compiler->smarty->use_reflection) {
                        if ($result = $this->injectObject($plugin, array('Smarty', 'Smarty_Internal_Template'),0)) {
                            if ($result[0] == 'Smarty') {
                                $object = '$_smarty_tpl->smarty, ';
                            } else {
                                $object = '$_smarty_tpl, ';
                            }
                        }
                    }
                    if ($object == null) {
                        $output = $plugin($single_modifier, $compiler);
                    } else {
                        $output = $plugin($object, $single_modifier, $compiler);
                    }
                }
                // check for plugin modifier
            } else if ($function = $compiler->getPlugin($modifier, Smarty::PLUGIN_MODIFIER)) {
                $object = $this->testParameter($function, $single_modifier, $compiler);
                // check if modifier allowed
                if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedModifier($modifier, $compiler)) {
                    $output = "{$function}({$object}{$params})";
                }
                // check if trusted PHP function
            } else if (is_callable($modifier)) {
                // check if modifier allowed
                if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedPhpModifier($modifier, $compiler)) {
                    $object = $this->testParameter($modifier, $single_modifier, $compiler);
                    $output = "{$modifier}({$object}{$params})";
                }
            } else {
                $compiler->trigger_template_error("unknown modifier \"" . $modifier . "\"", $compiler->lex->taglineno);
            }
        }
        return $output;
    }

    /**
    * Check number of required modifer parameter oand optionally return context object
    *
    * @param callback $function modifier callabck
    * @param array  $params parameter array
    * @param object $compiler  compiler object
    * @return string variable with context object or empty
    */
    private function testParameter($function, $params, $compiler) {
        $object = '';
        if ($compiler->smarty->use_reflection) {
            if ($result = $this->injectObject($function, array('Smarty', 'Smarty_Internal_Template'),0)) {
                if ($result[0] == 'Smarty') {
                    $object = '$_smarty_tpl->smarty, ';
                } else {
                    $object = '$_smarty_tpl, ';
                }
            }
            $no_required = $this->getNoOfRequiredParameter($function);
            $no_supplied = count($params);
            if ($result) {
                $no_supplied++;
            }
            if ($no_supplied < $no_required) {
                $compiler->trigger_template_error('missing required modifier parameter', $compiler->lex->taglineno);
            }
        }
        return $object;
    }
}

?>