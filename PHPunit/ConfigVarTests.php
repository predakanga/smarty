<?php
/**
* Smarty PHPunit tests of config  variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for config variable tests
*/
class ConfigVarTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test config varibale
    */
    public function testConfigVariable1()
    {
        $this->smarty->config_vars['confvar']='hello world';
        $this->assertEquals("hello world", $this->smarty->fetch('string:{#confvar#}'));
    } 
} 

?>
