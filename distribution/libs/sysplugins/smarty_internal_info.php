<?php

class Smarty_Internal_Info
{
    const PHP = 1;
    const PROPERTIES = 2;
    const FILESYSTEM = 4;
    const PLUGINS = 8;
    const REGISTERED = 16;
    const DEFAULTS = 32;
    const SECURITY = 34;
    
    const NOT_AVAILABLE = '#!$#notappliccable#$!#';
    
    protected static $ignored_properties = array(
        'smarty' => true,
        'parent' => true,
        'source' => true,
        'compiled' => true,
        'compiler' => true,
        'cached' => true,
        'tpl_vars' => true,
        'config_vars' => true,
        
        // working these extra
        'default_modifiers' => true,
        'autoload_filters' => true,

        // working these extra
        'template_dir' => true,
        'compile_dir' => true,
        'config_dir' => true,
        'cache_dir' => true,
        'plugins_dir' => true,
    );
    protected static $constructed = array(
        'use_reflection' => true,
        'debug_tpl' => true,
        'template_dir' => true,
        'compile_dir' => true,
        'config_dir' => true,
        'cache_dir' => true,
        'plugins_dir' => true,
    );
    protected static $flags = array(
        'caching' => array(
            'CACHING_OFF',
            'CACHING_LIFETIME_CURRENT',
            'CACHING_LIFETIME_SAVED',
        ),
        'compile_check' => array(
            'COMPILECHECK_OFF',
            'COMPILECHECK_ON',
            'COMPILECHECK_CACHEMISS',
        ),
        'php_handling' => array(
            'PHP_PASSTHRU',
            'PHP_QUOTE',
            'PHP_REMOVE',
            'PHP_ALLOW',
        ),
        'error_unassigned' => array(
            'UNASSIGNED_IGNORE',
            'UNASSIGNED_NOTICE',
            'UNASSIGNED_EXCEPTION',
        ),
    );
    
    protected $smarty = null;
    protected $template = null;

    protected $data = null;
    protected $errors = array();
    protected $warnings = array();
    
    protected $php = array();
    protected $properties = array();
    protected $plugins = array();
    protected $registered = array();
    protected $defaults = array();
    protected $security = array();
    protected $filesystem = array();
    
    public function __construct(Smarty $smarty, Smarty_Internal_Template $template = null)
    {
        $this->smarty = $smarty;
        $this->template = $template;
    }
    
    public function getArray($flags=0)
    {
        $this->analyze($flags);
        return $this->data;
    }
    
    public function getHtml($flags=0)
    {
        $this->analyze($flags);

        $tpl = new Smarty();
        $tpl->assign('data', $this->data);
        $tpl->assign('info', $this);
        $tpl->assign('_smarty', $this->smarty);
        $tpl->assign('_template', $this->template);
        // don't litter any template_c around
        $template = file_get_contents(SMARTY_DIR . 'info.tpl');
        return $tpl->fetch('eval:' . $template);
    }
    
    protected function analyze($flags=0)
    {
        $this->data = array(
            'na' => self::NOT_AVAILABLE,
            'version' => Smarty::SMARTY_VERSION,
            'bc' => $this->smarty instanceof SmartyBC,

            'errors' => &$this->errors,
            'warnings' => &$this->warnings,
        );
        
        if (!$flags || $flags & self::PHP) {
            $this->analyzeEnvironment();
            $this->data['php'] = $this->php;
        }
        if (!$flags || $flags & self::PROPERTIES) {
            $this->analyzeProperties();
            $this->data['properties'] = $this->properties;
        }
        if (!$flags || $flags & self::FILESYSTEM) {
            $this->analyzeFilesystem();
            $this->data['filesystem'] = $this->filesystem;
        }
        if (!$flags || $flags & self::PLUGINS) {
            $this->analyzePlugins();
            $this->data['plugins'] = $this->plugins;
        }
        if (!$flags || $flags & self::REGISTERED) {
            $this->analyzeRegistered();
            $this->data['registered'] = $this->registered;
        }
        if (!$flags || $flags & self::DEFAULTS) {
            $this->analyzeDefaults();
            $this->data['defaults'] = $this->defaults;
        }
        if (!$flags || $flags & self::SECURITY) {
            $this->analyzeSecurity();
            $this->data['security'] = $this->security;
        }
    }
    
    protected function analyzeTemplates()
    {
        // run through ALL templates, 
        //  test if they compile
        //  test if they contain deprecated plugins
        //  show inheritance paths
        //  show include paths
    }
    
    protected function analyzeEnvironment()
    {
        $this->php = array(
            'version' => phpversion(),
            'modules' => array(
                'mbstring' => array(
                    'name' => 'Multibyte String',
                    'href' => 'http://php.net/mbstring',
                    'available' => function_exists('mb_substr'),
                    'enabled' => SMARTY_MBSTRING,
                    'version' => phpversion('mbstring'),
                    'options' => array(
                        'func_overload' => array(
                            'name' => 'Function overload',
                            'is_value' => ini_get('mbstring.func_overload') & 2,
                            'need_value' => false,
                        ),
                    ),
                ),
                'pcre' => array(
                    'name' => 'Perl Compatible Regular Expressions',
                    'href' => 'http://php.net/pcre',
                    'available' => function_exists('preg_replace'),
                    'enabled' => true,
                    'version' => phpversion('pcre'),
                    'options' => array(
                        'backtrack_limit' => array(
                            'name' => 'Backtrack Limit',
                            'is_value' => ini_get('pcre.backtrack_limit'),
                            'need_value' => -1,
                        ),
                    ),
                )
            )
        );
    }
    
    protected function analyzeProperties()
    {
        $_clean_smarty = new Smarty();
        $template = null;
        $smarty = new ReflectionClass($this->smarty);
        if ($this->template) {
            $template = new ReflectionClass($this->template);
        }
        
        foreach ($smarty->getDefaultProperties() as $name => $value) {
            if ($name[0] == '_' || isset(self::$ignored_properties[$name])) {
                continue;
            }
            
            $property = $smarty->getProperty($name);
            if ($property->isStatic()) {
                continue;
            }
            
            $doc = $property->getDocComment();

            if (preg_match('#\* @internal\s#i', $doc, $matches)) {
                continue;
            }
            
            $type = null;
            if (preg_match('#\* @var (?<type>[a-zA-Z0-9_]+)\s#i', $doc, $matches)) {
                $type = $matches['type'];
            }
            
            $link = null;
            if (preg_match('#\* @link (?<link>[^\s]+)\s#i', $doc, $matches)) {
                $link = $matches['link'];
            }
            
            $_name = null;
            if (preg_match('#/\*{2}\s+\* (?<name>)\s\*#i', $doc, $matches)) {
                $_name = trim($matches['name']);
            }
            
            try {
                $_smarty_value = $this->sanitizeValue($name, $property->getValue($this->smarty), $type);
            } catch (ReflectionException $e) {
                $_smarty_value = self::NOT_AVAILABLE;
            }
            
            if ($template) {
                try {
                    $_property = $template->getProperty($name);
                    $_template_value = $this->sanitizeValue($name, $_property->getValue($this->template), $type);
                } catch (ReflectionException $e) {
                    $_template_value = self::NOT_AVAILABLE;
                }
            } else {
                $_template_value = self::NOT_AVAILABLE;
            }
            
            if (isset(self::$constructed[$name])) {
                $value = $_clean_smarty->$name;
            }
            $_default_value = $this->sanitizeValue($name, $value, $type);
            $this->properties[$name] = array(
                'name' => $_name ? $_name : $name,
                'type' => $type,
                'link' => $link,
                'default' => $_default_value,
                'flag' => isset(self::$flags[$name]),
                'smarty' => $_smarty_value,
                'template' => $_template_value,
                'error' => null,
                'warning' => null,
            );
            
            $this->properties[$name]['template_diff'] = $_smarty_value !== $_template_value;
            $this->properties[$name]['smarty_diff'] = $_smarty_value !== $_default_value;
        }
        
        ksort($this->properties);
        $this->analyzePropertiesPlausibility();
    }
    
    protected function analyzePropertiesPlausibility()
    {
        if ($this->properties['left_delimiter']['smarty'] === $this->properties['right_delimiter']['smarty']) {
            $message = "Left and Right Delimiters are equal";
            $this->errors['properties-left_delimiter'] = $message;
            $this->properties['left_delimiter']['error'] = $message;
            $this->properties['right_delimiter']['error'] = $message;
        }
    }
    
    protected function analyzeFilesystem()
    {
        $this->filesystem = array(
            'template_dir' => array(),
            'config_dir' => array(),
            'cache_dir' => array(),
            'compile_dir' => array(),
            'plugins_dir' => array(),
        );
        
        foreach ($this->smarty->getTemplateDir() as $key => $path) {
            $t = $this->analyzeDirectory($path, false, $this->smarty->use_include_path);
            $t['key'] = $key;
            $this->filesystem['template_dir'][] = $t;
            if ($t['error']) {
                $this->errors['filesystem-template_dir'] = "Template Directories";
            }
        }
        
        foreach ($this->smarty->getConfigDir() as $key => $path) {
            $t = $this->analyzeDirectory($path, false, $this->smarty->use_include_path);
            $t['key'] = $key;
            $this->filesystem['config_dir'][] = $t;
            if ($t['error']) {
                $this->errors['filesystem-config_dir'] = "Config Directories";
            }
        }
        
        foreach ($this->smarty->getPluginsDir() as $key => $path) {
            $t = $this->analyzeDirectory($path, false, $this->smarty->use_include_path);
            $this->filesystem['plugins_dir'][] = $t;
            if ($t['error']) {
                $this->errors['filesystem-plugins_dir'] = "Plugin Directories";
            }
        }
        
        // check if core plugins are available
        $_plugins = realpath(SMARTY_PLUGINS_DIR);
        foreach ($this->filesystem['plugins_dir'] as $_dir) {
            if ($_dir['realpath'] === $_plugins) {
                $_plugins = null;
                break;
            }
        }
        if ($_plugins) {
            $this->errors['filesystem-plugins_dir'] = "SMARTY_PLUGINS_DIR is missing";
        }
        
        $path = $this->smarty->getCompileDir();
        $t = $this->analyzeDirectory($path, true);
        $this->filesystem['compile_dir'][] = $t;
        if ($t['error']) {
            $this->errors['filesystem-compile_dir'] = "Compile Directory";
        }
        
        $path = $this->smarty->getCacheDir();
        $t = $this->analyzeDirectory($path, true);
        $this->filesystem['cache_dir'][] = $t;
        if ($t['error']) {
            $this->errors['filesystem-cache_dir'] = "Cache Directory";
        }
        
        ksort($this->filesystem);
    }
    
    protected function analyzeDirectory($path, $expect_writable=false, $use_include_path=false)
    {
        $includepath = null;
        $realpath = realpath($path);
        if (!$realpath && $use_include_path && !preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $path)) {
            $includepath = Smarty_Internal_Get_Include_Path::getIncludePath($path);
            if ($includepath) {
                $realpath = realpath($includepath);
            }
        }
        
        $_is_directory = $realpath ? is_dir($realpath) : null;
        $_is_readable = $_is_directory && $realpath ? is_readable($realpath) : null;
        $_is_writable = $_is_directory && $realpath ? is_writable($realpath) : null;
        
        if (!$realpath) {
            $error = 'Not Found';
        } elseif (!$_is_directory) {
            $error = 'Not a Directory';
        } elseif (!$_is_readable) {
            $error = 'Not Readable';
        } elseif (!$_is_writable && $expect_writable) {
            $error = 'Not Writable';
        } else {
            $error = null;
        }
        
        return array(
            'key' => null,
            'path' => $path,
            'realpath' => $realpath,
            'includepath' => $includepath,
            'is_dir' => $_is_directory,
            'readable' => $_is_readable,
            'writable' => $_is_writable,
            'error' => $error,
            'warning' => null,
        );
    }
    
    protected function analyzePlugins()
    {
        $this->plugins = array(
            'function' => array(),
            'modifier' => array(),
            'modifercompiler' => array(),
            'block' => array(),
            'compiler' => array(),
            'prefilter' => array(),
            'postfilter' => array(),
            'outputfilter' => array(),
            'variablefilter' => array(),
            'insert' => array(),
            'resource' => array(),
            'cacheresource' => array(),
        );
        
        // import plugins_dir
        if (!$this->filesystem) {
            $this->analyzeFilesystem();
            $directories = $this->filesystem['plugins_dir'];
            $this->filesystem = array();
        } else {
            $directories = $this->filesystem['plugins_dir'];
        }
        
        // scan plugins_dir
        foreach ($directories as $dir) {
            if (!$dir['realpath']) {
                continue;
            }
            
            $iterator = new DirectoryIterator($dir['realpath']);
            foreach ($iterator as $file) {
                if ($file->isDot()) {
                    continue;
                }
                
                $parts = explode('.', $file->getFilename());
                $type = strtolower($parts[0]);
                if (count($parts) != 3 || !isset($this->plugins[$type])) {
                    continue;
                }
                
                $name = $parts[1];
                $this->plugins[$type][$name] = array(
                    'name' => $name,
                    'type' => $type,
                    'file' => $file->getFilename(),
                    'realpath' => $dir['realpath'] . DS . $file->getFilename(),
                    'function' => null,
                    'registered' => null,
                    'autoloaded' => true,
                    'signature' => null,
                    'nocache' => null,
                    'cache_attr' => null,
                    'error' => null,
                    'warning' => null,
                );
            }
        }
        
        // TODO: scan registered_plugins
        // TODO: scan registered_filters

        ksort($this->plugins);
        foreach ($this->plugins as &$plugins) {
            ksort($plugins);
        }
    }
    
    protected function analyzeRegistered()
    {
        
    }
    
    protected function analyzeDefaults()
    {
        
    }
    
    protected function analyzeSecurity()
    {
        
    }
    
    protected function sanitizeValue($name, $value, $type)
    {
        switch ($type) {
            case 'boolean':
                $value = (boolean) $value;
                break;

            case 'integer':
                $value = (int) $value;
                break;
            
            case 'float':
                $value = (int) $value;
                break;
            
            case 'array':
                $value = (array) $value;
                break;
            
            case 'string':
                $value = (string) $value;
                break;
            
            case 'callable':
                $value = $value ? '(function)' : null;
                break;
        }

        if (isset(self::$flags[$name])) {
            $value = isset(self::$flags[$name][$value]) ? self::$flags[$name][$value] : $value;
        }
        
        return $value;
    }

}