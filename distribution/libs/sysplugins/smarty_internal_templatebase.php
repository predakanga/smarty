<?php
/**
 * Smarty Internal Plugin Smarty Template  Base
 *
 * This file contains the basic shared methodes for template handling
 *
 * @package Smarty
 * @subpackage Template
 * @author Uwe Tews
 */

/**
 * Class with shared template methodes
 *
 * @package Smarty
 * @subpackage Template
 */
abstract class Smarty_Internal_TemplateBase extends Smarty_Internal_Data {

    /**
     * fetches a rendered Smarty template
     *
     * @param string $template          the resource handle of the template file or template object
     * @param mixed  $cache_id          cache id to be used with this template
     * @param mixed  $compile_id        compile id to be used with this template
     * @param object $parent            next higher level of Smarty variables
     * @param bool   $display           true: display, false: fetch
     * @param bool   $no_output_filter  if true do not run output filter
     * @param bool   $merge_tpl_vars    if true parent template variables merged in to local scope
     * @return string rendered template output
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $no_output_filter = false, $merge_tpl_vars = true)
    {
        if ($template === null && $this instanceof $this->template_class) {
            $template = $this;
        }
        if (!empty($cache_id) && is_object($cache_id)) {
            $parent = $cache_id;
            $cache_id = null;
        }
        if ($parent === null && ($this instanceof Smarty || is_string($template))) {
            $parent = $this;
        }
        // create template object if necessary
        $_template = ($template instanceof $this->template_class)
            ? $template
            : $this->smarty->createTemplate($template, $cache_id, $compile_id, $parent, false);
        // if called by Smarty object make sure we use current caching status
        if ($this instanceof Smarty) {
            $_template->caching = $this->caching;
        }
        // merge all variable from scopes into template
        if ($merge_tpl_vars && $_template->parent != null) {
            // save local variables
            $saved_tpl_vars = clone $_template->tpl_vars;
            $saved_config_vars = clone $_template->config_vars;
            $_template->tpl_vars = clone $_template->parent->tpl_vars;
            $_template->config_vars = clone $_template->parent->config_vars;
            // merge local vars
            foreach ($saved_tpl_vars as $key => $var) {
                $_template->tpl_vars->$key = $var;
            }
            foreach ($saved_config_vars as $key => $var) {
                $_template->config_vars->$key = $var;
            }
        }

        // dummy local smarty variable
        if (!isset($_template->tpl_vars->smarty)) {
            $_template->tpl_vars->smarty = new Smarty_Variable;
        }
        if (isset($this->smarty->error_reporting)) {
            $_smarty_old_error_level = error_reporting($this->smarty->error_reporting);
        }
        // check URL debugging control
        if (!$this->smarty->debugging && $this->smarty->debugging_ctrl == 'URL') {
            if (isset($_SERVER['QUERY_STRING'])) {
                $_query_string = $_SERVER['QUERY_STRING'];
            } else {
                $_query_string = '';
            }
            if (false !== strpos($_query_string, $this->smarty->smarty_debug_id)) {
                if (false !== strpos($_query_string, $this->smarty->smarty_debug_id . '=on')) {
                    // enable debugging for this browser session
                    setcookie('SMARTY_DEBUG', true);
                    $this->smarty->debugging = true;
                } elseif (false !== strpos($_query_string, $this->smarty->smarty_debug_id . '=off')) {
                    // disable debugging for this browser session
                    setcookie('SMARTY_DEBUG', false);
                    $this->smarty->debugging = false;
                } else {
                    // enable debugging for this page
                    $this->smarty->debugging = true;
                }
            } else {
                if (isset($_COOKIE['SMARTY_DEBUG'])) {
                    $this->smarty->debugging = true;
                }
            }
        }
        // must reset merge template date
        $_template->smarty->merged_templates_func = array();
        // get rendered template
        // disable caching for evaluated code
        if ($_template->source->recompiled) {
            $_template->caching = false;
        }
        // checks if template exists
        if (!$_template->source->exists) {
            if ($_template->parent instanceof Smarty_Internal_Template) {
                $parent_resource = " in '{$_template->parent->template_resource}'";
            } else {
                $parent_resource = '';
            }
            throw new SmartyException("Unable to load template {$_template->source->type} '{$_template->source->name}'{$parent_resource}");
        }
        if ($_template->caching == Smarty::CACHING_LIFETIME_CURRENT || $_template->caching == Smarty::CACHING_LIFETIME_SAVED) {
            $_output = $_template->cached->getRenderedTemplate($this, $_template, $no_output_filter);
        } else {
            $_output = $_template->compiled->getRenderedTemplate($this, $_template);
        }
        if ((!$this->caching || $_template->source->recompiled) && !$no_output_filter && (isset($this->smarty->autoload_filters['output']) || isset($this->smarty->registered_filters['output']))) {
            $_output = Smarty_Internal_Filter_Handler::runFilter('output', $_output, $_template);
        }
        if (isset($this->error_reporting)) {
            error_reporting($_smarty_old_error_level);
        }
        // display or fetch
        if ($display) {
            if ($this->caching && $this->cache_modified_check) {
                $_isCached = $_template->isCached() && !$_template->has_nocache_code;
                $_last_modified_date = @substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 0, strpos($_SERVER['HTTP_IF_MODIFIED_SINCE'], 'GMT') + 3);
                if ($_isCached && $_template->cached->timestamp <= strtotime($_last_modified_date)) {
                    switch (PHP_SAPI) {
                        case 'cgi':         // php-cgi < 5.3
                        case 'cgi-fcgi':    // php-cgi >= 5.3
                        case 'fpm-fcgi':    // php-fpm >= 5.3.3
                            header('Status: 304 Not Modified');
                            break;

                        case 'cli':
                            if (/* ^phpunit */!empty($_SERVER['SMARTY_PHPUNIT_DISABLE_HEADERS'])/* phpunit$ */) {
                                $_SERVER['SMARTY_PHPUNIT_HEADERS'][] = '304 Not Modified';
                            }
                            break;

                        default:
                            header('HTTP/1.1 304 Not Modified');
                            break;
                    }
                } else {
                    switch (PHP_SAPI) {
                        case 'cli':
                            if (/* ^phpunit */!empty($_SERVER['SMARTY_PHPUNIT_DISABLE_HEADERS'])/* phpunit$ */) {
                                $_SERVER['SMARTY_PHPUNIT_HEADERS'][] = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', $_template->cached->timestamp) . ' GMT';
                            }
                            break;

                        default:
                            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $_template->cached->timestamp) . ' GMT');
                            break;
                    }
                    echo $_output;
                }
            } else {
                echo $_output;
            }
            // debug output
            if ($this->smarty->debugging) {
                Smarty_Internal_Debug::display_debug($this);
            }
            if ($merge_tpl_vars && $_template->parent != null) {
                // restore local variables
                $_template->tpl_vars = $saved_tpl_vars;
                $_template->config_vars =  $saved_config_vars;
            }
            return;
        } else {
            // return fetched content
            if ($merge_tpl_vars && $_template->parent != null) {
                // restore local variables
                $_template->tpl_vars = $saved_tpl_vars;
                $_template->config_vars =  $saved_config_vars;
            }
            return $_output;
        }
    }

    /**
     * displays a Smarty template
     *
     * @param string $template   the resource handle of the template file or template object
     * @param mixed  $cache_id   cache id to be used with this template
     * @param mixed  $compile_id compile id to be used with this template
     * @param object $parent     next higher level of Smarty variables
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        // display template
        $this->fetch($template, $cache_id, $compile_id, $parent, true);
    }

    /**
     * test if cache is valid
     *
     * @param string|object $template   the resource handle of the template file or template object
     * @param mixed         $cache_id   cache id to be used with this template
     * @param mixed         $compile_id compile id to be used with this template
     * @param object        $parent     next higher level of Smarty variables
     * @return boolean cache status
     */
    public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        if ($template === null && $this instanceof $this->template_class) {
            return $this->cached->valid;
        }
        if (!($template instanceof $this->template_class)) {
            if ($parent === null) {
                $parent = $this;
            }
            $template = $this->smarty->createTemplate($template, $cache_id, $compile_id, $parent, false);
        }
        // return cache status of template
        return $template->cached->valid;
    }

    /**
     * creates a data object
     *
     * @param object $parent next higher level of Smarty variables
     * @returns Smarty_Data data object
     */
    public function createData($parent = null)
    {
        return new Smarty_Data($parent, $this);
    }

    /**
     * Registers plugin to be used in templates
     *
     * @param string   $type       plugin type
     * @param string   $tag        name of template tag
     * @param callback $callback   PHP callback to register
     * @param boolean  $cacheable  if true (default) this fuction is cachable
     * @param array    $cache_attr caching attributes if any
     * @throws SmartyException when the plugin tag is invalid
     */
    public function registerPlugin($type, $tag, $callback, $cacheable = true, $cache_attr = null)
    {
        if (isset($this->smarty->registered_plugins[$type][$tag])) {
            throw new SmartyException("Plugin tag \"{$tag}\" already registered");
        } elseif (!is_callable($callback)) {
            throw new SmartyException("Plugin \"{$tag}\" not callable");
        } else {
            $this->smarty->registered_plugins[$type][$tag] = array($callback, (bool) $cacheable, (array) $cache_attr);
        }
    }

    /**
     * Unregister Plugin
     *
     * @param string $type of plugin
     * @param string $tag name of plugin
     */
    public function unregisterPlugin($type, $tag)
    {
        if (isset($this->smarty->registered_plugins[$type][$tag])) {
            unset($this->smarty->registered_plugins[$type][$tag]);
        }
    }

    /**
     * Registers a resource to fetch a template
     *
     * @param string $type name of resource type
     * @param Smarty_Resource|array $callback or instance of Smarty_Resource, or array of callbacks to handle resource (deprecated)
     */
    public function registerResource($type, $callback)
    {
        $this->smarty->registered_resources[$type] = $callback instanceof Smarty_Resource ? $callback : array($callback, false);
    }

    /**
     * Unregisters a resource
     *
     * @param string $type name of resource type
     */
    public function unregisterResource($type)
    {
        if (isset($this->smarty->registered_resources[$type])) {
            unset($this->smarty->registered_resources[$type]);
        }
    }

    /**
     * Registers a cache resource to cache a template's output
     *
     * @param string               $type     name of cache resource type
     * @param Smarty_CacheResource $callback instance of Smarty_CacheResource to handle output caching
     */
    public function registerCacheResource($type, Smarty_CacheResource $callback)
    {
        $this->smarty->registered_cache_resources[$type] = $callback;
    }

    /**
     * Unregisters a cache resource
     *
     * @param string $type name of cache resource type
     */
    public function unregisterCacheResource($type)
    {
        if (isset($this->smarty->registered_cache_resources[$type])) {
            unset($this->smarty->registered_cache_resources[$type]);
        }
    }

    /**
     * Registers object to be used in templates
     *
     * @param string  $object        name of template object
     * @param object  $object_impl   the referenced PHP object to register
     * @param array   $allowed       list of allowed methods (empty = all)
     * @param boolean $smarty_args   smarty argument format, else traditional
     * @param array   $block_methods list of block-methods
     * @param array $block_functs list of methods that are block format
     * @throws SmartyException if any of the methods in $allowed or $block_methods are invalid
     */
    public function registerObject($object_name, $object_impl, $allowed = array(), $smarty_args = true, $block_methods = array())
    {
        // test if allowed methodes callable
        if (!empty($allowed)) {
            foreach ((array) $allowed as $method) {
                if (!is_callable(array($object_impl, $method))) {
                    throw new SmartyException("Undefined method '$method' in registered object");
                }
            }
        }
        // test if block methodes callable
        if (!empty($block_methods)) {
            foreach ((array) $block_methods as $method) {
                if (!is_callable(array($object_impl, $method))) {
                    throw new SmartyException("Undefined method '$method' in registered object");
                }
            }
        }
        // register the object
        $this->smarty->registered_objects[$object_name] =
            array($object_impl, (array) $allowed, (boolean) $smarty_args, (array) $block_methods);
    }

    /**
     * return a reference to a registered object
     *
     * @param string $name object name
     * @return object
     * @throws SmartyException if no such object is found
     */
    public function getRegisteredObject($name)
    {
        if (!isset($this->smarty->registered_objects[$name])) {
            throw new SmartyException("'$name' is not a registered object");
        }
        if (!is_object($this->smarty->registered_objects[$name][0])) {
            throw new SmartyException("registered '$name' is not an object");
        }
        return $this->smarty->registered_objects[$name][0];
    }

    /**
     * unregister an object
     *
     * @param string $name object name
     * @throws SmartyException if no such object is found
     */
    public function unregisterObject($name)
    {
        unset($this->smarty->registered_objects[$name]);
        return;
    }

    /**
     * Registers static classes to be used in templates
     *
     * @param string $class name of template class
     * @param string $class_impl the referenced PHP class to register
     * @throws SmartyException if $class_impl does not refer to an existing class
     */
    public function registerClass($class_name, $class_impl)
    {
        // test if exists
        if (!class_exists($class_impl)) {
            throw new SmartyException("Undefined class '$class_impl' in register template class");
        }
        // register the class
        $this->smarty->registered_classes[$class_name] = $class_impl;
    }

    /**
     * Registers a default plugin handler
     *
     * @param callable $callback class/method name
     * @throws SmartyException if $callback is not callable
     */
    public function registerDefaultPluginHandler($callback)
    {
        if (is_callable($callback)) {
            $this->smarty->default_plugin_handler_func = $callback;
        } else {
            throw new SmartyException("Default plugin handler '$callback' not callable");
        }
    }

    /**
     * Registers a default template handler
     *
     * @param callable $callback class/method name
     * @throws SmartyException if $callback is not callable
     */
    public function registerDefaultTemplateHandler($callback)
    {
        if (is_callable($callback)) {
            $this->smarty->default_template_handler_func = $callback;
        } else {
            throw new SmartyException("Default template handler '$callback' not callable");
        }
    }

    /**
     * Registers a default variable handler
     *
     * @param callable $callback class/method name
     * @throws SmartyException if $callback is not callable
     */
    public function registerDefaultVariableHandler($callback)
    {
        if (is_callable($callback)) {
            $this->smarty->default_variable_handler_func = $callback;
        } else {
            throw new SmartyException("Default variable handler '$callback' not callable");
        }
    }

    /**
     * Registers a default config variable handler
     *
     * @param callable $callback class/method name
     * @throws SmartyException if $callback is not callable
     */
    public function registerDefaultConfigVariableHandler($callback)
    {
        if (is_callable($callback)) {
            $this->smarty->default_config_variable_handler_func = $callback;
        } else {
            throw new SmartyException("Default config variable handler '$callback' not callable");
        }
    }

    /**
     * Registers a default template handler
     *
     * @param callable $callback class/method name
     * @throws SmartyException if $callback is not callable
     */
    public function registerDefaultConfigHandler($callback)
    {
        if (is_callable($callback)) {
            $this->smarty->default_config_handler_func = $callback;
        } else {
            throw new SmartyException("Default config handler '$callback' not callable");
        }
    }

    /**
     * Registers a filter function
     *
     * @param string $type filter type
     * @param callback $callback
     */
    public function registerFilter($type, $callback)
    {
        $this->smarty->registered_filters[$type][$this->_get_filter_name($callback)] = $callback;
    }

    /**
     * Unregisters a filter function
     *
     * @param string $type filter type
     * @param callback $callback
     */
    public function unregisterFilter($type, $callback)
    {
        $name = $this->_get_filter_name($callback);
        if (isset($this->smarty->registered_filters[$type][$name])) {
            unset($this->smarty->registered_filters[$type][$name]);
        }
    }

    /**
     * Return internal filter name
     *
     * @param callback $function_name
     */
    public function _get_filter_name($function_name)
    {
        if (is_array($function_name)) {
            $_class_name = (is_object($function_name[0]) ?
                            get_class($function_name[0]) : $function_name[0]);
            return $_class_name . '_' . $function_name[1];
        } else {
            return $function_name;
        }
    }

    /**
     * load a filter of specified type and name
     *
     * @param string $type filter type
     * @param string $name filter name
     * @return bool
     */
    public function loadFilter($type, $name)
    {
        $_plugin = "smarty_{$type}filter_{$name}";
        $_filter_name = $_plugin;
        if ($this->smarty->loadPlugin($_plugin)) {
            if (class_exists($_plugin, false)) {
                $_plugin = array($_plugin, 'execute');
            }
            if (is_callable($_plugin)) {
                $this->smarty->registered_filters[$type][$_filter_name] = $_plugin;
                return true;
            }
        }
        throw new SmartyException("{$type}filter \"{$name}\" not callable");
        return false;
    }

    /**
     * unload a filter of specified type and name
     *
     * @param string $type filter type
     * @param string $name filter name
     * @return bool
     */
    public function unloadFilter($type, $name)
    {
        $_filter_name = "smarty_{$type}filter_{$name}";
        if (isset($this->smarty->registered_filters[$type][$_filter_name])) {
            unset ($this->smarty->registered_filters[$type][$_filter_name]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * preg_replace callback to convert camelcase getter/setter to underscore property names
     *
     * @param string $match match string
     * @return string  replacemant
     */
    private function replaceCamelcase($match) {
        return "_" . strtolower($match[1]);
    }

    /**
     * Handle unknown class methods
     *
     * @param string $name unknown method-name
     * @param array  $args argument array
     */
    public function __call($name, $args)
    {
        static $_prefixes = array('set' => true, 'get' => true);
        static $_resolved_property_name = array();
        static $_resolved_property_source = array();

        // method of Smarty object?
        if (method_exists($this->smarty, $name)) {
            return call_user_func_array(array($this->smarty, $name), $args);
        }
        // see if this is a set/get for a property
        $first3 = strtolower(substr($name, 0, 3));
        if (isset($_prefixes[$first3]) && isset($name[3]) && $name[3] !== '_') {
            if (isset($_resolved_property_name[$name])) {
                $property_name = $_resolved_property_name[$name];
            } else {
                // try to keep case correct for future PHP 6.0 case-sensitive class methods
                // lcfirst() not available < PHP 5.3.0, so improvise
                $property_name = strtolower(substr($name, 3, 1)) . substr($name, 4);
                // convert camel case to underscored name
                $property_name = preg_replace_callback('/([A-Z])/', array($this,'replaceCamelcase'), $property_name);
                $_resolved_property_name[$name] = $property_name;
            }
            if (isset($_resolved_property_source[$property_name])) {
                $_is_this = $_resolved_property_source[$property_name];
            } else {
                $_is_this = null;
                if (property_exists($this, $property_name)) {
                    $_is_this = true;
                } else if (property_exists($this->smarty, $property_name)) {
                    $_is_this = false;
                }
                $_resolved_property_source[$property_name] = $_is_this;
            }
            if ($_is_this) {
                if ($first3 == 'get')
                    return $this->$property_name;
                else
                    return $this->$property_name = $args[0];
            } else if ($_is_this === false) {
                if ($first3 == 'get')
                    return $this->smarty->$property_name;
                else
                    return $this->smarty->$property_name = $args[0];
            } else {
                throw new SmartyException("property '$property_name' does not exist.");
                return false;
            }
        }
        // must be unknown
        throw new SmartyException("Call of unknown method '$name'.");
    }

}

?>