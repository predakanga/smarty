<?php
/**
* Smarty PHPunit basic core function tests
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class core function tests
*/
class CoreTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
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
    * loadPlugin must exist
    */
    public function testLoadPluginExist()
    {
        $this->assertTrue(method_exists('Smarty', 'loadPlugin'));
    } 

    /**
    * loadPlugin test unkown plugin
    */
    public function testLoadPluginErrorReturn()
    {
        $this->assertFalse($this->smarty->loadPlugin('Smarty_Not_Known'));
    } 
    /**
    * loadPlugin test Smarty_Internal_Base exists
    */
    public function testLoadPluginSmartyInternalBase()
    {
        $this->assertTrue($this->smarty->loadPlugin('Smarty_Internal_Base'));
    } 
    /**
    * loadPlugin test Smarty_Internal_PluginBase exists
    */
    public function testLoadPluginSmartyInternalPluginBase()
    {
        $this->assertTrue($this->smarty->loadPlugin('Smarty_Internal_PluginBase'));
    } 
    /**
    * loadPlugin test Smarty_Internal_Plugin_Handler exists
    */
    public function testLoadPluginSmartyInternalPluginHandler()
    {
        $this->assertTrue($this->smarty->loadPlugin('Smarty_Internal_Plugin_Handler'));
    }
    /**
    * loadPlugin test Smarty_Internal_Debug exists
    */
    public function testLoadPluginSmartyInternalDebug()
    {
        $this->assertTrue($this->smarty->loadPlugin('Smarty_Internal_Debug'));
    } 
    /**
    * loadPlugin test $template_class exists
    */
    public function testLoadPluginSmartyTemplateClass()
    {
        $this->assertTrue($this->smarty->loadPlugin($this->smarty->template_class));
    } 
} 

?>
