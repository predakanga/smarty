<?php
/**
* Smarty PHPunit tests register_modifier / unregister_modifier methods
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for register_modifier / unregister_modifier methods tests
*/
class RegisterModifierTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
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
    * test register_modifier method for function
    */
    public function testRegisterModifier()
    {
        $this->smarty->register_modifier('testmodifier', 'mymodifier');
        $this->assertEquals('mymodifier', $this->smarty->registered_plugins['testmodifier'][1]);
        $this->assertEquals('modifier', $this->smarty->registered_plugins['testmodifier'][0]);
    } 
    /**
    * test register_modifier method for class
    */
    public function testRegisterModifierClass()
    {
        $this->smarty->register_modifier('testmodifier', array('mymodifierclass', 'execute'));
        $this->assertEquals('modifier', $this->smarty->registered_plugins['testmodifier'][0]);
    } 
    /**
    * test register_modifier method for object
    */
    public function testRegisterModifierObject()
    {
        $mymodifier_object = new mymodifierclass;
        $this->smarty->register_modifier('testmodifier', array($mymodifier_object, 'execute'));
        $this->assertEquals('modifier', $this->smarty->registered_plugins['testmodifier'][0]);
    } 
    /**
    * test unregister_modifier method
    */
    public function testUnregisterModifier()
    {
        $this->smarty->register_modifier('testmodifier', 'mymodifier');
        $this->smarty->unregister_modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['testmodifier']));
    } 
    /**
    * test unregister_modifier method not registered
    */
    public function testUnregisterModifierNotRegistered()
    {
        $this->smarty->unregister_modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['testmodifier']));
    } 
    /**
    * test unregister_modifier method other registered
    */
    public function testUnregisterModifierOtherRegistered()
    {
        $this->smarty->register_block('testmodifier', 'mymodifier');
        $this->smarty->unregister_modifier('testmodifier');
        $this->assertTrue(isset($this->smarty->registered_plugins['testmodifier']));
    } 
} 
function mymodifier($params, &$smarty)
{
    return "hello world";
} 
class mymodifierclass {
    function execute($params, &$smarty)
    {
        return "hello world";
    } 
} 

?>
