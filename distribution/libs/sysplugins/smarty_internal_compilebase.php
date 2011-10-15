<?php
/**
* Smarty Internal Plugin CompileBase
*
* @package Smarty
* @subpackage Compiler
* @author Uwe Tews
*/

/**
* This class does extend all internal compile plugins
*
* @package Smarty
* @subpackage Compiler
*/
abstract class Smarty_Internal_CompileBase {

    /**
    * Array of names of required attribute required by tag
    *
    * @var array
    */
    public $required_attributes = array();
    /**
    * Array of names of optional attribute required by tag
    * use array('_any') if there is no restriction of attributes names
    *
    * @var array
    */
    public $optional_attributes = array();
    /**
    * Shorttag attribute order defined by its names
    *
    * @var array
    */
    public $shorttag_order = array();
    /**
    * Array of names of valid option flags
    *
    * @var array
    */
    public $option_flags = array('nocache');

    /**
    * This function checks if the attributes passed are valid
    *
    * The attributes passed for the tag to compile are checked against the list of required and
    * optional attributes. Required attributes must be present. Optional attributes are check against
    * the corresponding list. The keyword '_any' specifies that any attribute will be accepted
    * as valid
    *
    * @param object $compiler   compiler object
    * @param array  $attributes attributes applied to the tag
    * @return array of mapped attributes for further processing
    */
    public function getAttributes($compiler, $attributes)
    {
        $_indexed_attr = array();
        // loop over attributes
        foreach ($attributes as $key => $mixed) {
            // shorthand ?
            if (!is_array($mixed)) {
                // option flag ?
                if (in_array(trim($mixed, '\'"'), $this->option_flags)) {
                    $_indexed_attr[trim($mixed, '\'"')] = true;
                    // shorthand attribute ?
                } else if (isset($this->shorttag_order[$key])) {
                    $_indexed_attr[$this->shorttag_order[$key]] = $mixed;
                } else {
                    // too many shorthands
                    $compiler->trigger_template_error('too many shorthand attributes', $compiler->lex->taglineno);
                }
                // named attribute
            } else {
                $kv = each($mixed);
                // option flag?
                if (in_array($kv['key'], $this->option_flags)) {
                    if (is_bool($kv['value'])) {
                        $_indexed_attr[$kv['key']] = $kv['value'];
                    } else if (is_string($kv['value']) && in_array(trim($kv['value'], '\'"'), array('true', 'false'))) {
                        if (trim($kv['value']) == 'true') {
                            $_indexed_attr[$kv['key']] = true;
                        } else {
                            $_indexed_attr[$kv['key']] = false;
                        }
                    } else if (is_numeric($kv['value']) && in_array($kv['value'], array(0, 1))) {
                        if ($kv['value'] == 1) {
                            $_indexed_attr[$kv['key']] = true;
                        } else {
                            $_indexed_attr[$kv['key']] = false;
                        }
                    } else {
                        $compiler->trigger_template_error("illegal value of option flag \"{$kv['key']}\"", $compiler->lex->taglineno);
                    }
                    // must be named attribute
                } else {
                    reset($mixed);
                    $_indexed_attr[key($mixed)] = $mixed[key($mixed)];
                }
            }
        }
        // check if all required attributes present
        foreach ($this->required_attributes as $attr) {
            if (!array_key_exists($attr, $_indexed_attr)) {
                $compiler->trigger_template_error("missing \"" . $attr . "\" attribute", $compiler->lex->taglineno);
            }
        }
        // check for unallowed attributes
        if ($this->optional_attributes != array('_any')) {
            $tmp_array = array_merge($this->required_attributes, $this->optional_attributes, $this->option_flags);
            foreach ($_indexed_attr as $key => $dummy) {
                if (!in_array($key, $tmp_array) && $key !== 0) {
                    $compiler->trigger_template_error("unexpected \"" . $key . "\" attribute", $compiler->lex->taglineno);
                }
            }
        }
        // default 'false' for all option flags not set
        foreach ($this->option_flags as $flag) {
            if (!isset($_indexed_attr[$flag])) {
                $_indexed_attr[$flag] = false;
            }
        }

        return $_indexed_attr;
    }

    /**
    * Push opening tag name on stack
    *
    * Optionally additional data can be saved on stack
    *
    * @param object    $compiler   compiler object
    * @param string    $openTag    the opening tag's name
    * @param mixed     $data       optional data saved
    */
    public function openTag($compiler, $openTag, $data = null)
    {
        array_push($compiler->_tag_stack, array($openTag, $data));
    }

    /**
    * Pop closing tag
    *
    * Raise an error if this stack-top doesn't match with expected opening tags
    *
    * @param object       $compiler    compiler object
    * @param array|string $expectedTag the expected opening tag names
    * @return mixed any type the opening tag's name or saved data
    */
    public function closeTag($compiler, $expectedTag)
    {
        if (count($compiler->_tag_stack) > 0) {
            // get stacked info
            list($_openTag, $_data) = array_pop($compiler->_tag_stack);
            // open tag must match with the expected ones
            if (in_array($_openTag, (array) $expectedTag)) {
                if (is_null($_data)) {
                    // return opening tag
                    return $_openTag;
                } else {
                    // return restored data
                    return $_data;
                }
            }
            // wrong nesting of tags
            $compiler->trigger_template_error("unclosed {" . $_openTag . "} tag");
            return;
        }
        // wrong nesting of tags
        $compiler->trigger_template_error("unexpected closing tag", $compiler->lex->taglineno);
        return;
    }

    /**
    * Get Annotation From PHPdoc comments
    *
    * @param mixed callback function or class method to be parsed
    * @param string parameter name
    * @return mixed data
    */
    public function getAnnotation($callback, $parameter) {
        if (is_string($callback)) {
            $reflection = new ReflectionFunction($callback);
        } elseif (is_array($callback)) {
            $cn = is_object($callback[0]) ? 'ReflectionObject' : 'ReflectionClass';
            $reflection = new $cn($callback[0]);
            $reflection = $reflection->getMethod($callback[1]);
        } else {
            throw new Excption("callback must be function name (string) or object/class method array");
        }
        // get PHPdoc data
        $doc = $reflection->getDocComment();
        if ($doc && preg_match('#@' . $parameter . '(.+)$#m', $doc, $matches)) {
            if (isset($matches)) {
                $m = explode(',', $matches[1]);
                $m = array_map('trim', $m);
                if (count($m) == 1 && $m[0] == '') {
                    return true;
                } else {
                    return $m;
                }
            }
        }
        return false;
    }

    /**
    * Find object position by type-hint
    *
    * @param mixed callback function or class method to be parsed
    * @param array objects array of class name to look for
    * @param mixed position  int => check specified position; null => scan all parameter
    * @return mixed  false if failed, array of call found and position
    */
    public function injectObject($callback, $objects, $position = null) {
        // get function reflection
        if (is_string($callback) || $callback instanceof Closure) {
            $reflection = new ReflectionFunction($callback);
        } elseif (is_array($callback)) {
            $cn = is_object($callback[0]) ? 'ReflectionObject' : 'ReflectionClass';
            $reflection = new $cn($callback[0]);
            $reflection = $reflection->getMethod($callback[1]);
        } else {
            throw new SmartyException("callback must be function name (string) or object/class method array");
        }

        // get list of parameters
        $args = $reflection->getParameters();
        if (!$args) {
            return false;
        }
        if ($position != null) {
            $arg = $args[$position];
            $class = $arg->getClass();
            if ($class) {
                if (in_array($class->name, $objects)) {
                    return array($class->name, $position);
                } else {
                    return false;
                }
            }
        }
        // search for objects
        for ($pos = 0, $count = count($args); $pos < $count; $pos++){
            $arg = $args[$pos];
            $class = $arg->getClass();
            if ($class) {
                if (in_array($class->name, $objects)) {
                    return array($class->name, $pos);
                }
            }
        }
        return false;
    }
    /**
    * Get create plugin parameter string
    *
    * @param mixed callback function or class method to be parsed
    * @param array $params parameter from template
    * @return mixed data
    * @throws SmartyCompilerException
    */
    public function getPluginParameterString($callback, $params, $compiler, $block, $cache_attr = null) {
        if (is_string($callback)) {
            $reflection = new ReflectionFunction($callback);
        } elseif (is_array($callback)) {
            $cn = is_object($callback[0]) ? 'ReflectionObject' : 'ReflectionClass';
            $reflection = new $cn($callback[0]);
            $reflection = $reflection->getMethod($callback[1]);
        } else {
            throw new CompilerExcption("callback must be function name (string) or object/class method array");
        }
        $object = '$_smarty_tpl';
        if ($compiler->smarty->use_reflection && $result = $this->injectObject($callback, array('Smarty', 'Smarty_Internal_Template'))) {
            if ($result[0] == 'Smarty') {
                $object = '$_smarty_tpl->smarty';
            }
            if ($result[0] == 0 && !$block) {
                $par_array = array();
                $par_names = array();
                $parameters = $reflection->getParameters();
                // lose first argument, since it must've been Smarty or Smarty_Internal_Template
                array_shift($parameters);
                foreach ($parameters as $par) {
                    $name = $par->getName();
                    $par_names[$name] = true;
                    $optional = $par->isOptional();
                    if (isset($params[$name])) {
                        if ($compiler->template->caching && is_array($cache_attr) && in_array($name, $cache_attr)) {
                            $value = str_replace("'","^#^",$params[$name]);
                            $par_array[] = "'$name'=>^#^.var_export($value,true).^#^";
                        } else {
                            $par_array[] = $params[$name];
                        }
                    } elseif ($optional) {
                        $value = $par->getDefaultValue();
                        $par_array[] = var_export($value,true);
                    } else {
                        throw new SmartyCompilerException("missing parameter {$name}");
                    }
                }
                
                foreach ($params as $key => $value) {
                    if (is_int($key)) {
                        $par_array[] = "$key=>$value";
                    } elseif (!isset($par_names[$key])) {
                        throw new SmartyCompilerException("undefined parameter {$name}");
                    }
                }

                return $object . ', ' . implode(",", $par_array);
            }
        }
        $par_array = array();
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $par_array[] = "$key=>$value";
            } elseif ($compiler->template->caching && is_array($cache_attr) && in_array($key, $cache_attr)) {
                $value = str_replace("'","^#^",$value);
                $par_array[] = "'$key'=>^#^.var_export($value,true).^#^";
            } else {
                $par_array[] = "'$key'=>$value";
            }
        }
        $arr = 'array(' . implode(",", $par_array) . ')';
        if ($block) {
            return array('par' => $arr, 'obj' => $object);
        } else {
        return 'array(' . implode(",", $par_array) . '),'.$object;
        }
    }
}
?>