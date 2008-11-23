<?php
/**
* Smarty PHPunit tests for security
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for security test
*/
class SecurityTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test trusted function plugin
    */
    public function testTrustedFunctionPlugin()
    {
        $this->assertEquals("10", $this->smarty->fetch('string:{counter start=10}'));
    } 

    /**
    * test not trusted function plugin
    */
    public function testNotTrustedFunctionPlugin()
    {
        $this->smarty->security_policy->function_plugins = array('null');
        try {
            ob_start();
            $this->smarty->fetch('string:{counter start=10}');
        } 
        catch (Exception $e) {
            $this->assertContains('function plugin "counter" not allowed by security setting', ob_get_clean());
            return;
        } 
        $this->fail('Exception for not trusted function plugin has not been raised.');
    } 

    /**
    * test not trusted function plugin
    */
    public function testDisabledTrustedFunctionPlugin()
    {
        $this->smarty->security_policy->function_plugins = array('null');
        $this->smarty->security = false;
        $this->assertEquals("10", $this->smarty->fetch('string:{counter start=10}'));
    } 

} 

?>
