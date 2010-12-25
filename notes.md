# Notes #



-----
## Questions ##

blah


-----
## UnitTesting ##

blah


-----
## Things I noticed ##

Loading of plugins depends on a Smarty instance (for plugin_dir). But if a Plugin is successfully loaded in Smarty-Instance-1 (knowing the plugin_dir) and then used in Smarty-Instance-2 (NOT knowing the plugin_dir) the Plugin is still executed properly. I wouldn't call this a bug, but it certainly is odd behaviour.


-----
## Clean this up ##

### addTrailingDS() ###

<code>if (strpos('/\\', substr($_plugin_dir, -1)) === false) {
    $_plugin_dir .= DS;
}</code>

is reoccuring piece of code that should be moved to its own function

### getFilepathDirectory() ###

$_template->smarty->use_sub_dirs thingie with DS and ^ should be a central function

### sanitizeCompileId() ###

<code>$_compile_id = isset($compile_id) ? preg_replace('![^\w\|]+!', '_', $compile_id) : null;</code>


-----
## Notes For Documentation ##

### Resource API ###

<code>CustomResource</code>s have to extend <code>Smarty_Resource</code>. 

If a Resource's templates should not be run through the Smarty compiler, the <code>CustomResource</code> may extend <code>Smarty_Resource_Uncompiled</code>. The Resource Handler must then implement the function <code>renderUncompiled(Smarty_Internal_Template $_template)</code>. <code>$_template</code> is a reference to the current template and contains all assigned variables which the implementor can access via <code>$_template->smarty->getTemplateVars()</code>. These Resources simply echo their rendered content to the output stream. The rendered output will be output-cached if the Smarty instance was configured accordingly.

If the Resource's compiled templates should not be cached on disk, the <code>CustomResource</code> may extend <code>Smarty_Resource_Recompiled</code>. These Resources are compiled every time they are accessed. This may be an expensive overhead. 

#### Registering Custom Resource Handler Instances ####

Since *Smarty Version XYZ* Resource Handler objects can be registerted with a Smarty instance:
<code>$smarty->registerResource( 'resName', new CustomResource() )</code>.

#### Autoloaded Custom Resource Handler Plugins ####

A <code>CustomResource</code> can be lazyloaded by naming the class <code>Smarty_Resource_Foobar</code> in a file called <code>resource.foobar.php</code> in the <code>plugin_dir</code>. 

#### Registering Custom Resource Handler Callbacks ####

For backward compatibilty it is still possible to register a resource as follows, but note that this is deprecated since *Smarty Version XYZ*:

<pre><code>$smarty->registerResource( 'foobar', array(
	'the_resource_source',
	'the_resource_timestamp',
   	'the_resource_secure',
   	'the_resource_trusted'
))</code></pre>

The order of the callbacks must be maintained. How the functions are called is up to the implementor.

#### Autoloaded Custom Resource Handler Callbacks ####

For backward compatibilty it is still possible to autoload resource callbacks by placing them in a file called <code>resource.foobar.php</code> in the <code>plugin_dir</code>. The callbacks must be named in the following pattern: <code>smarty_resource_foobar_source</code>, <code>smarty_resource_foobar_timestamp</code>, <code>smarty_resource_foobar_secure</code>, <code>smarty_resource_foobar_trusted</code>
	

