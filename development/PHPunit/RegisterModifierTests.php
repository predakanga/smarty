<?php
/**
* Smarty PHPunit tests register->modifier / unregister->modifier methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register->modifier / unregister->modifier methods tests
*/
class RegisterModifierTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test register->modifier method for function
    */
    public function testRegisterModifier()
    {
        $this->smarty->register->modifier('testmodifier', 'mymodifier');
        $this->assertEquals('mymodifier', $this->smarty->registered_plugins['modifier']['testmodifier'][0]);
    } 
    /**
    * test unregister->modifier method
    */
    public function testUnregisterModifier()
    {
        $this->smarty->register->modifier('testmodifier', 'mymodifier');
        $this->smarty->unregister->modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['modifier']['testmodifier']));
    } 
    /**
    * test unregister->modifier method not registered
    */
    public function testUnregisterModifierNotRegistered()
    {
        $this->smarty->unregister->modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['modifier']['testmodifier']));
    } 
    /**
    * test unregister->modifier method other registered
    */
    public function testUnregisterModifierOtherRegistered()
    {
        $this->smarty->register->block('testmodifier', 'mymodifier');
        $this->smarty->unregister->modifier('testmodifier');
        $this->assertTrue(isset($this->smarty->registered_plugins['block']['testmodifier']));
    } 
} 
function mymodifier($params, &$smarty)
{
    return "hello world";
} 
class mymodifierclass {
    static function execute($params, &$smarty)
    {
        return "hello world";
    } 
} 

?>
