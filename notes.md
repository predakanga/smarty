# Notes #



## Questions ##

blah

## UnitTesting ##

* ResourcePluginTests is using the resource 'db' which does not exist - WTF?
* ResourcePluginTests add test for registerResource( 'foobar', new Smarty_Resource_Foobar() )
* ResourcePluginTests add test for Smarty_Resource_Foobar from plugin_dir 



## Things I noticed ##

Loading of plugins depends on a Smarty instance (for plugin_dir). But if a Plugin is successfully loaded in Smarty-Instance-1 (knowing the plugin_dir) and then used in Smarty-Instance-2 (NOT knowing the plugin_dir) the Plugin is still executed properly. I wouldn't call this a bug, but it certainly is odd behaviour.



## left to do ##

* what is isEvaluated really doing?
* Smarty_Resource::$isEvaluated and Smarty_Resource::$usesCompiler are flags that are never changed.
* replace isEvaluted and usesCompiler by appropriate Interfaces


## Clean this up ##

### addTrailingDS() ###

<code>if (strpos('/\\', substr($_plugin_dir, -1)) === false) {
    $_plugin_dir .= DS;
}</code>

is reoccuring piece of code that should be moved to its own function

### getFilepathDirectory() ###

$_template->smarty->use_sub_dirs thingie with DS and ^ should be a central function