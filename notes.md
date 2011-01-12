# Notes #

## changes ##

* added SMARTY_MBSTRING to generalize MBString detection
* added argument $lc_rest to modifier.capitalize to lower-case anything but the first character of a word
* changed strip modifier to consider unicode white-space, too
* changed wordwrap modifier to accept UTF-8 strings
* changed count_sentences modifier to consider unicode characters and treat sequences delimited by ? and ! as sentences, too
* added argument $double_encode to modifier.escape (applies to html and htmlall only)
* changed escape modifier to be UTF-8 compliant
* changed textformat block to be UTF-8 compliant
* optimized performance of mailto function
* fixed spacify modifier so characters are not prepended and appended, made it unicode compatible
* fixed truncate modifier to properly use mb_string if possible
* removed UTF-8 frenzy from count_characters modifier
* fixed count_words modifier to treat "hello-world" as a single word like str_count_words() does
* removed UTF-8 frenzy from upper modifier

next up: *make modifiers UTF-8 safe and sane*

### UTF-8 insanity ###

* modifiercompiler.lower.php

### Afterwards ###

* add convert modifier smarty_modifier_convert($string, $from_encoding="ISO-8859-1", $to=SMARTY_RESOURCE_CHAR_SET)
* recheck all preg_* functions using \s. They will fail unicode spaces like U+2004 (THREE-PER-EM SPACE) - unless the /u modifier is in place!
* is it wise to use PCRE with /u everywhere? What if the user requires some obscure charset like KOI8-R or EUC-JP - these patterns will fail. use the mb_ereg functions instead? 
	* http://www.devcomments.com/ode-to-mb-ereg-functions-i5786.htm


-----
## ToDo ##

* add <code>modifier.unescape.php</code> to transform entities to characters http://www.smarty.net/forums/viewtopic.php?p=69382#69382
* make <code>modifier.escape.php</code> a compilerfunction to speed up the numerous escape:"html" calls
* rewrite <code>function.html_select_date.php</code> and <code>function.html_select_time.php</code> for speed and clarity
* rewrite function.fetch.php to use [file_get_contents](http://php.net/file_get_contents) and [context](http://php.net/manual/en/function.stream-context-create.php) for HTTP/FTP access.
* [template_dir selection](http://groups.google.com/group/smarty-developers/browse_thread/thread/e3cdb246ddb7fda2) could be done with <code>$smarty->template_dir['foo'] = "/some/path/to/templates";</code><code>"file:[foo]bar.tpl"</code>


-----
## Questions ##

* may an exec('rm -rf '. escapeshellarg($directory)); speed up clearing the file cacheResource?


-----
## UnitTesting ##

blah


-----
## Things I noticed ##

Loading of plugins depends on a Smarty instance (for plugin_dir). But if a Plugin is successfully loaded in Smarty-Instance-1 (knowing the plugin_dir) and then used in Smarty-Instance-2 (NOT knowing the plugin_dir) the Plugin is still executed properly. I wouldn't call this a bug, but it certainly is odd behaviour.

<code>$default_template_handler_func</code> is executed after the FS is searched for files. Thus it triggers only after a minimum of 2 failed <code>file_exist()</code>s. If the default handler were to be used as an expander for "virtual" directories, this would yield performance issues. "virtual" directories could be something like <code>ModuleXY/foo.tpl</code> expanded to <code>/some/path/modules/xy/templates/foo.tpl</code>. Which is in fact a feature I would like to employ. Seeing that this applies to <code>file</code> and <code>extend</code> resources, it does not make sense to extend these classes seperately. I would stick to the callback, but not use it as a fallback handler, rather as something to prepare any given filepath.

Plugins make use of <code>trigger_error()</code>. Deliberately or some Smarty2 relict? looks wrong somehow. Smarty must assist in debugging, but not force people to simply switch off error_reporting.

compiled templates show an awful number of @ (silencers). This may cost performance as error suppression doesn't come cheap

-----
## Clean this up ##

### getFilepathDirectory() (duplicate code reduction) ###

$_template->smarty->use_sub_dirs thingie with DS and ^ should be a central function

### sanitizeCompileId() (duplicate code reduction) ###

<code>$_compile_id = isset($compile_id) ? preg_replace('![^\w\|]+!', '_', $compile_id) : null;</code>


-----
## Wishlist ##

1. Plugins lazyloaded from plugin_dir should be able to control their cachability. Something along the lines of <code>Smarty::setPluginMeta($pluginName, $cacheable, $cache_attrs);</code>.
2. Move options like $use_sub_dirs to a new SmartySettings facility. Cleans up the Smarty Object, makes Settings reusable, allows settings for plugins like <code>$smartySettings->set("cache.file.exclude", "/.svn/Si")</code>. This should not apply to $caching and other Smarty instance oriented settings.
	* $basedir in function.html_image.php
3. Sane support for namespaces introduced in PHP 5.3 <code>registerClass('Foo','\some\name\spaced\Foo');</code> is a first step, <code>{\some\name\spaced\Foo::helloWorld()}</code> another. <code>registerNamespace('\foo\bar\bla', 'fbb')</code> may even be translated to <code>use \foo\bar\bla as fbb;</code>. <code>{use "/foo/bar" as "fb"} {fn\ClassName::helloWorld()}</code> may be nice as well. Where namespaces are in play, people usually know how to use spl_autoload properly.
4. <code>{if $foobar}</code> should translate to <code>{if isset($foobar) && $foobar}</code> to simplify template syntax while keeping the code clean.


-----
## Notes For Documentation ##

### CacheResource API ###

<code>CacheResource</code>s have to extend <code>Smarty_CacheResource</code>. For a simple integration <code>Smarty_CacheResource_Custom</code> reduces the API to mere read and write calls to the data store. See the example given in <code>development/PHPUnit/PHPUnitplugins/cacheresource.mysql.php</code>.

If data stores such as memcache are to be implemented, <code>Smarty_CacheResource_KeyValueStore</code> is there to help. It manages everything around deep cache-groups on a level suitable for most key/value stores. See the example given in <code>development/PHPUnit/PHPUnitplugins/cacheresource.memcache.php</code>.

#### Registering Custom CacheResource Handler Instances ####

Since *Smarty Version XYZ* Cache Resource Handler objects can be registerted with a Smarty instance:
<code>$smarty->registerCacheResource( 'resName', new CustomCacheResource() )</code>.

#### Autoloaded Custom Resource Handler Plugins ####

A <code>CustomCacheResource</code> can be lazyloaded by naming the class <code>Smarty_CacheResource_Foobar</code> in a file called <code>cacheresource.foobar.php</code> in the <code>plugin_dir</code>. 

### Resource API ###

<code>CustomResource</code>s have to extend <code>Smarty_Resource</code>. For a simple integration <code>Smarty_Resource_Custom</code> allows the integrator to implement a single method to serve templates. See the example below.

If a Resource's templates should not be run through the Smarty compiler, the <code>CustomResource</code> may extend <code>Smarty_Resource_Uncompiled</code>. The Resource Handler must then implement the function <code>renderUncompiled(Smarty_Internal_Template $_template)</code>. <code>$_template</code> is a reference to the current template and contains all assigned variables which the implementor can access via <code>$_template->smarty->getTemplateVars()</code>. These Resources simply echo their rendered content to the output stream. The rendered output will be output-cached if the Smarty instance was configured accordingly. See <code>libs/sysplugins/smarty_internal_resource_php.php</code> for an example.

If the Resource's compiled templates should not be cached on disk, the <code>CustomResource</code> may extend <code>Smarty_Resource_Recompiled</code>. These Resources are compiled every time they are accessed. This may be an expensive overhead. See <code>libs/sysplugins/smarty_internal_resource_eval.php</code> for an example.

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
	
#### Custom Resource Examples ####

<pre><code>class Smarty_Resource_Mysql extends Smarty_Resource_Custom {
    // PDO instance
    protected $db;
    // prepared fetch() statement
    protected $fetch;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:dbname=test;host=127.0.0.1", "smarty", "smarty");
        } catch (PDOException $e) {
            throw new SmartyException('Mysql Resource failed: ' . $e->getMessage());
        }
        $this->fetch = $this->db->prepare('SELECT modified, source FROM templates WHERE name = :name');
    }
    
    /**
     * Fetch a template and its modification time from database
     *
     * @param string $name template name
     * @param string $source template source
     * @param integer $mtime template modification timestamp (epoch)
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $this->fetch->execute(array('name' => $name));
        $row = $this->fetch->fetch();
        $this->fetch->closeCursor();
        if ($row) {
            $source = $row['source'];
            $mtime = strtotime($row['modified']);
        } else {
            $source = null;
            $mtime = null;
        }
    }
}</code></pre>

<pre><code>class Smarty_Resource_Mysql extends Smarty_Resource_Custom {
    // PDO instance
    protected $db;
    // prepared fetch() statement
    protected $fetch;
    // prepared fetchTimestamp() statement
    protected $mtime;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:dbname=test;host=127.0.0.1", "smarty", "smarty");
        } catch (PDOException $e) {
            throw new SmartyException('Mysql Resource failed: ' . $e->getMessage());
        }
        $this->fetch = $this->db->prepare('SELECT modified, source FROM templates WHERE name = :name');
        $this->mtime = $this->db->prepare('SELECT modified FROM templates WHERE name = :name');
    }
    
    /**
     * Fetch a template and its modification time from database
     *
     * @param string $name template name
     * @param string $source template source
     * @param integer $mtime template modification timestamp (epoch)
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $this->fetch->execute(array('name' => $name));
        $row = $this->fetch->fetch();
        $this->fetch->closeCursor();
        if ($row) {
            $source = $row['source'];
            $mtime = strtotime($row['modified']);
        } else {
            $source = null;
            $mtime = null;
        }
    }
    
    /**
     * Fetch a template's modification time from database
     *
     * @note implementing this method is optional. Only implement it if modification times can be accessed faster than loading the comple template source.
     * @param string $name template name
     * @return integer timestamp (epoch) the template was modified
     */
    protected function fetchTimestamp($name) {
        $this->mtime->execute(array('name' => $name));
        $mtime = $this->mtime->fetchColumn();
        $this->mtime->closeCursor();
        return strtotime($mtime);
    }
}</code></pre>

Both examples require the following table:

<pre><code>CREATE TABLE IF NOT EXISTS `templates` (
  `name` varchar(100) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `source` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `templates` (`name`, `modified`, `source`) VALUES ('test.tpl', "2010-12-25 22:00:00", '{$x="hello world"}{$x}');</code></pre>