<?php

class Smarty_Internal_Info
{
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
    protected $properties = array();
    
    public function __construct(Smarty $smarty, Smarty_Internal_Template $template = null)
    {
        $this->smarty = $smarty;
        $this->template = $template;
    }
    
    public function getArray()
    {
        return $this->data;
    }
    
    public function getHtml()
    {
        $this->analyze();

        $tpl = new Smarty();
        $tpl->assign('data', $this->data);
        $tpl->assign('info', $this);
        $tpl->assign('_smarty', $this->smarty);
        $tpl->assign('_template', $this->template);
        // don't litter any template_c around
        $template = file_get_contents(SMARTY_DIR . 'info.tpl');
        return $tpl->fetch('eval:' . $template);
    }
    
    protected function analyze()
    {
        $this->analyzeProperties();
        // TODO: analyze directories (see testInstall())
        // TODO: analyze security
        // TODO: analyze cache resource handler availabilty
        // TODO: analyze plugins

        $this->data = array(
            'na' => '#!$#notappliccable#$!#',
            'version' => Smarty::SMARTY_VERSION,
            'bc' => $this->smarty instanceof SmartyBC,
            'php' => array(
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
            ),
            'properties' => $this->properties,
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
                $_smarty_value = '#!$#notappliccable#$!#';
            }
            
            if ($template) {
                try {
                    $_property = $template->getProperty($name);
                    $_template_value = $this->sanitizeValue($name, $_property->getValue($this->template), $type);
                } catch (ReflectionException $e) {
                    $_template_value = '#!$#notappliccable#$!#';
                }
            } else {
                $_template_value = '#!$#notappliccable#$!#';
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
            );
            
            $this->properties[$name]['template_diff'] = $_smarty_value !== $_template_value;
            $this->properties[$name]['smarty_diff'] = $_smarty_value !== $_default_value;
        }
        
        ksort($this->properties);
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