# Notes #


## Questions ##

* Why is smarty_internal_resource_extends.php::getTemplateSource() using $this->_rdl AND $this->smarty->left_delimiter?
* 


## UnitTesting ##

* ResourcePluginTests is using the resource 'db' which does not exist - WTF?


## registerResource() API ##

Since Smarty_Resource can now be extended by users, it makes sense to overload Smarty::registerResource() to accept an object of the type Smarty_Resource. An array of functions can still be passed and processed by Smarty_Internal_Resource_Registered to stay BC - it should be deprecated, though.



### left to do ###

* what is isEvaluated really doing?
* getTemplateTimestampTypeName() abstraction
* resolve Smarty_Internal_Resource_Registered::__construct() issue
* Smarty_Internal_Template cleanup for new Resource API
* Smarty_Resource::$isEvaluated and Smarty_Resource::$usesCompiler are flags that are never changed.


## Clean this up ##

### addTrailingDS() ###

<code>if (strpos('/\\', substr($_plugin_dir, -1)) === false) {
    $_plugin_dir .= DS;
}</code>

is reoccuring piece of code that should be moved to its own function

### getFilepathDirectory() ###

$_template->smarty->use_sub_dirs thingie with DS and ^ should be a central function