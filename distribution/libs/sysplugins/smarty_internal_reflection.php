<?php
/**
* Smarty refection
*
* @package Smarty
* @subpackage Reflection
* @author Uwe Tews
* @author Rodney Rehm
*/

/**
* Smarty Internal Reflection Class
*
* @package Smarty
* @subpackage Reflection
*/
class Smarty_Internal_Reflection {

    /**
    * Get Annotation From PHPdoc comments
    *
    * @param mixed callback function or class method to be parsed
    * @param string parameter name
    * @return mixed data
    */
    static function getAnnotation($callback, $parameter) {
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
    * Find object position bay type-hint
    *
    * @param mixed callback function or class method to be parsed
    * @param array objects array of class name to look for
    * @param mixed position  int => check specified position; null => scan all parameter
    * @return mixed  false if failed, array of call found and position
    */
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
}
?>