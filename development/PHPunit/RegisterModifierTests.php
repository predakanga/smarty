<?php
/**
* Smarty PHPunit tests register_modifier / unregister_modifier methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register_modifier / unregister_modifier methods tests
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
    * test register_modifier method for function
    */
    public function testRegisterModifier()
    {
        $this->smarty->register_modifier('testmodifier', 'mymodifier');
        $this->assertEquals('mymodifier', $this->smarty->registered_plugins['modifier']['testmodifier'][0]);
    } 
    /**
    * test unregister_modifier method
    */
    public function testUnregisterModifier()
    {
        $this->smarty->register_modifier('testmodifier', 'mymodifier');
        $this->smarty->unregister_modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['modifier']['testmodifier']));
    } 
    /**
    * test unregister_modifier method not registered
    */
    public function testUnregisterModifierNotRegistered()
    {
        $this->smarty->unregister_modifier('testmodifier');
        $this->assertFalse(isset($this->smarty->registered_plugins['modifier']['testmodifier']));
    } 
    /**
    * test unregister_modifier method other registered
    */
    public function testUnregisterModifierOtherRegistered()
    {
        $this->smarty->register_block('testmodifier', 'mymodifier');
        $this->smarty->unregister_modifier('testmodifier');
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
