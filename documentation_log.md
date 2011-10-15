# Documentation ToDo #

these changes must reflect on the documentation at some point…

## Undocumented ##

* $error_unassigned
* $_file_perms
* $_dir_perms
* $default_plugin_handler_func
* Smarty_Internal_Templatebase::registerDefaultTemplateHandler()
* Smarty_Internal_Templatebase::registerDefaultConfigHandler()
* Smarty_Internal_Template::fetch() display() isCached() clear() into Smarty::createTemplate() docs?
* $use_reflection


## Properties ##

* Smarty::$default_variable_handler_func_ - Smarty::registerDefaultVariableHandler()
    * (boolean) function($name, &$value)
* Smarty::$default_config_variable_handler_func - Smarty::registerDefaultConfigVariableHandler()
    * (boolean) function($name, &$value)


## Syntax ##

* {$x = $k cachevalue} 
* {\some\namespaced\ClassName::CONST}


## Plugins ##

* smarty_modifier_foobar(Smarty $smarty, $string, …) vs. smarty_modifier_foobar($string, …)
* smarty_function_foobar(Smarty $smarty, $param1, $param2) vs, smarty_modifier_foobar($params, Smarty_Internal_Template $template)
* smarty_block_foobar(Smarty $smarty, $content, $$repeat, $param1, $param2) vs, smarty_modifier_foobar($params, $content, Smarty_Internal_Template $template, &$repeat)


## Functions ##


## Modifiers ##

