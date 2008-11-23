<?php
/**
* Smarty plugin
* 
* @package Smarty
* @subpackage PluginsConfiguration
* @author Uwe Tews 
*/ 
    define('SMARTY_PHP_QUOTE', 1);
    define('SMARTY_PHP_REMOVE', 2);
    define('SMARTY_PHP_ALLOW', 3);
/**
* This class does contain the security settings
*/
class Smarty_Security_Policy {
    /**
    * This determines how Smarty handles "<?php ... ?>" tags in templates.
    * possible values:
    * <ul>
    *   <li>SMARTY_PHP_QUOTE    -> escape tags as entities</li>
    *   <li>SMARTY_PHP_REMOVE   -> remove php tags</li>
    *   <li>SMARTY_PHP_ALLOW    -> execute php tags</li>
    * </ul>
    * 
    * @var integer 
    */
    public $php_handling = SMARTY_PHP_ALLOW;

    /**
    * This is the list of template directories that are considered secure.
    * One directory per array element. 
    * $template_dir is in this list implicitly.
    * 
    * @var array 
    */
    public $secure_dir = array();


    /**
    * This is an array of directories where trusted php scripts reside.
    * {@link $security} is disabled during their inclusion/execution.
    * 
    * @var array 
    */
    public $trusted_dir = array();

    /**
    * This is an array of standard compiler tags.
    *
    * If empty all compiler tags are allowed.
    * If set to 'none' none is allowed.
    * @var array 
    */
    public $compiler_tags = array('assign','capture','debug','eval','for','foreach','if','include',
            'insert','nocache');

    /**
    * This is an array of trusted function plugins.
    *
    * If empty all plugins are allowed.
    * If set to 'none' none is allowed.
    * @var array 
    */
    public $function_plugins = array('counter','mailto');

    /**
    * This is an array of trusted PHP functions.
    *
    * If empty all functions are allowed.
    * If set to 'none' none is allowed.
    * @var array 
    */
    public $php_functions = array('isset', 'empty',
            'count', 'sizeof','in_array', 'is_array','time');

    /**
    * This is an array of trusted modifers.
    *
    * If empty all modifiers are allowed.
    * If set to 'none' none is allowed.
    * @var array 
    */
    public $modifiers = array('escape','count');
} 

?>
