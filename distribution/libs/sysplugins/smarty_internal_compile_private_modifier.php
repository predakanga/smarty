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
                $object = '';
                if ($compiler->smarty->use_reflection) {
                    if ($result = Smarty_Internal_Reflection::injectObject($function, array('Smarty', 'Smarty_Internal_Template'),0)) {
                        if ($result[0] == 'Smarty') {
                            $object = '$_smarty_tpl->smarty, ';
                        } else {
                            $object = '$_smarty_tpl, ';
                        }
                    }
                }
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
                        if ($result = Smarty_Internal_Reflection::injectObject('smarty_modifiercompiler_'.$modifier, array('Smarty', 'Smarty_Internal_Template'),0)) {
                            if ($result[0] == 'Smarty') {
                                $object = $compiler->template->smarty;
                            } else {
                                $object = $compiler->template;
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
                $object = '';
                if ($compiler->smarty->use_reflection) {
                    if ($result = Smarty_Internal_Reflection::injectObject('smarty_modifier_'.$modifier, array('Smarty', 'Smarty_Internal_Template'),0)) {
                        if ($result[0] == 'Smarty') {
                            $object = '$_smarty_tpl->smarty, ';
                        } else {
                            $object = '$_smarty_tpl, ';
                        }
                    }
                }
                // check if modifier allowed
                if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedModifier($modifier, $compiler)) {
                    $output = "{$function}({$object}{$params})";
                }
                // check if trusted PHP function
            } else if (is_callable($modifier)) {
                // check if modifier allowed
                if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedPhpModifier($modifier, $compiler)) {
                    $object = '';
                    if ($compiler->smarty->use_reflection) {
                        if ($result = Smarty_Internal_Reflection::injectObject($modifier, array('Smarty', 'Smarty_Internal_Template'),0)) {
                            if ($result[0] == 'Smarty') {
                                $object = '$_smarty_tpl->smarty, ';
                            } else {
                                $object = '$_smarty_tpl, ';
                            }
                        }
                    }
                    $output = "{$modifier}({$object}{$params})";
                }
            } else {
                $compiler->trigger_template_error("unknown modifier \"" . $modifier . "\"", $compiler->lex->taglineno);
            }
        }
        return $output;
    }

}

?>