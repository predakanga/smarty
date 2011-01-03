<?php

error_reporting( E_ALL | E_WARNING | E_NOTICE | E_STRICT );
ini_set( 'display_errors', 'On' );

require_once( dirname( __FILE__ ) .'/../distribution/libs/Smarty.class.php' );

$smarty = new Smarty();
$smarty->caching = true;
$smarty->debugging = true;
$smarty->force_compile = true;
$smarty->plugins_dir[] = realpath( dirname(__FILE__)."/../development/PHPunit/PHPunitplugins/" );
$smarty->plugins_dir[] = realpath( dirname(__FILE__)."/plugins/" );
$smarty->cache_dir = realpath( dirname(__FILE__)."/cache/" );

class smarty_function_classy2 {
    
    public static function run(array $params, Smarty_Internal_Template $template)
    {
        return 'go classy 2!';
    }
}

class smarty_function_classy2_nocache {
    public static function cache()
    {
        return array('foo');
    }
    
    public static function run(array $params, Smarty_Internal_Template $template)
    {
        return 'go classy2 nocache by definiton!';
    }
}

$smarty->registerPlugin( 'function', 'classy2', 'smarty_function_classy2' );
$smarty->registerPlugin( 'function', 'classy2_nocache', 'smarty_function_classy2_nocache', false, array('foo') );

$smarty->display('plugin.tpl');
