<?php

class smarty_function_classy_nocache {
    public static $nocache = true;
    public static $cache_attr = array('foo');
    public static function run(array $params, Smarty_Internal_Template $template)
    {
        return 'go classy nocache by definiton!';
    }
}