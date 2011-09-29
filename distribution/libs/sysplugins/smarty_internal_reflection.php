<?php
/**
* Smarty refection
*
* @package Smarty
* @subpackage Reflection
* @author Uwe Tews
*/

/**
* Smarty Internal Reflection Class
*
* @package Smarty
* @subpackage Reflection
*/
class Smarty_Internal_Reflection {

    /**
    * Get Smarty Meta Parameter From PHPdoc comments
    *
    * @param mixed callback function or class method to be parsed
    * @param string parameter name
    * @return mixed data
    */
    static function getMetaParameter($callback, $parameter) {
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
        if ($doc && preg_match('#@' . $parameter . '((.+)$(,|\s+))+?#m', $doc, $matches)) {
            if (isset($matches)) {
                if (count($matches) == 1) {
                    return true;
                }
            }
        }
        return false;
    }
    static function injectObject($callback, $objects, $position = null) {
        // get function reflection
        if (is_string($callback) || $callback instanceof Closure) {
            $reflection = new ReflectionFunction($callback);
        } elseif (is_array($callback)) {
            $cn = is_object($callback[0]) ? 'ReflectionObject' : 'ReflectionClass';
            $reflection = new $cn($callback[0]);
            $reflection = $reflection->getMethod($callback[1]);
        } else {
            throw new Exception("callback must be function name (string) or object/class method array");
        }

        // get list of parameters
        $args = $reflection->getParameters();
        if (!$args) {
            return false;
        }
        // search for objects
        for ($pos = 0, $count = count($args); $pos < $count; $pos++){
            $arg = $args[$pos];
            $class = $arg->getClass();
            if ($class) {
                if (in_array($class->name, $objects)) {
                    if ($position == null || $position == $pos) {
                        return array($class->name, $pos);
                    } elseif ($position != null || $position != $pos) {
                        throw new Exception("object not in exspected position");
                    }
                }
            }
        }
        return false;
    }
}
?>