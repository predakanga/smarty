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
*/
class Smarty_Internal_Compile_Private_Modifier extends Smarty_Internal_CompileBase {
	/**
	* Compiles code for modifier execution
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
		// loop over list of modifiers
		foreach ($parameter['modifierlist'] as $single_modifier) {
			$modifier = $single_modifier[0];
			$single_modifier[0] = $output;
			$params = implode(',', $single_modifier);
			if (!isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier])) {
				// try registering modifier on-the-fly
				if (is_callable($compiler->smarty->default_plugin_handler_func)) {
					if ($path=call_user_func($compiler->smarty->default_plugin_handler_func, $modifier, Smarty::PLUGIN_MODIFIER, $this->smarty)) {
						if (isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier])) {
							if (is_string($path) && is_file($path)) {
								$function = $compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier][0];
								if ($compiler->template->caching && ($this->nocache || $this->tag_nocache)) {
									$compiler->template->required_plugins['nocache'][$modifier][Smarty::PLUGIN_MODIFIER]['file'] = $path;
									$compiler->template->required_plugins['nocache'][$modifier][Smarty::PLUGIN_MODIFIER]['function'] = $function;
								} else {
									$compiler->template->required_plugins['compiled'][$modifier][Smarty::PLUGIN_MODIFIER]['file'] = $path;
									$compiler->template->required_plugins['compiled'][$modifier][Smarty::PLUGIN_MODIFIER]['function'] = $function;
								}
							}
						}
					}
				}
			}
			// check for registered modifier
			if (isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier])) {
				$function = $compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][$modifier][0];
				if (!is_array($function)) {
					$output = "{$function}({$params})";
				} else {
					if (is_object($function[0])) {
						$output = '$_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER][\'' . $modifier . '\'][0][0]->' . $function[1] . '(' . $params . ')';
					} else {
						$output = $function[0] . '::' . $function[1] . '(' . $params . ')';
					}
				}
//			} else if (!isset($this->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0])) {
//				if (is_callable($this->smarty->default_plugin_handler_func)) {
//					call_user_func($this->smarty->default_plugin_handler_func, $modifier, Smarty::PLUGIN_MODIFIERCOMPILER, $this->smarty);
//				}
			} else if (isset($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0])) {
				$output = call_user_func($compiler->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0], $single_modifier, $compiler->smarty);
				// check for plugin modifiercompiler
			} else if ($compiler->smarty->loadPlugin('smarty_modifiercompiler_' . $modifier)) {
				$plugin = 'smarty_modifiercompiler_' . $modifier;
				$output = $plugin($single_modifier, $compiler);
				// check for plugin modifier
			} else if ($function = $compiler->getPlugin($modifier, Smarty::PLUGIN_MODIFIER)) {
				$output = "{$function}({$params})";
				// check if trusted PHP function
			} else if (is_callable($modifier)) {
				// check if modifier allowed
				if (!is_object($compiler->smarty->security_policy) || $compiler->smarty->security_policy->isTrustedModifier($modifier, $compiler)) {
					$output = "{$modifier}({$params})";
				}
			} else {
				$compiler->trigger_template_error ("unknown modifier \"" . $modifier . "\"", $compiler->lex->taglineno);
			}
		}
		return $output;
	}
}
?>