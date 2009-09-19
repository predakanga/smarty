<?php
/**
* Smarty PHPunit tests register_function / unregister_function methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register_function / unregister_function methods tests
*/
class RegisterFunctionTests extends PHPUnit_Framework_TestCase {
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
    * test register_function method for function
    */
    public function testRegisterFunction()
    {
        $this->smarty->register_function('testfunction', 'myfunction');
        $this->assertEquals('myfunction', $this->smarty->registered_plugins['testfunction'][1]);
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
        $this->assertEquals('hello world 1', $this->smarty->fetch('string:{testfunction value=1}'));
    } 
    /**
    * test register_function method for class
    */
    public function testRegisterFunctionClass()
    {
        $this->smarty->register_function('testfunction', array('myfunctionclass', 'execute'));
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
        $this->assertEquals('hello world 2', $this->smarty->fetch('string:{testfunction value=2}'));
    } 
    /**
    * test register_function method for object
    */
    public function testRegisterFunctionObject()
    {
        $myfunction_object = new myfunctionclass;
        $this->smarty->register_function('testfunction', array($myfunction_object, 'execute'));
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
        $this->assertEquals('hello world 3', $this->smarty->fetch('string:{testfunction value=3}'));
    } 
    public function testRegisterFunctionCaching1()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->force_compile = true;
        $this->smarty->assign('x', 0);
        $this->smarty->assign('y', 10);
        $this->smarty->register_function('testfunction', 'myfunction');
        $this->assertEquals('hello world 0 10', $this->smarty->fetch('test_register_function.tpl'));
    } 
    public function testRegisterFunctionCaching2()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->assign('x', 1);
        $this->smarty->assign('y', 20);
        $this->smarty->register_function('testfunction', 'myfunction');
        $this->assertEquals('hello world 0 10', $this->smarty->fetch('test_register_function.tpl'));
    } 
    public function testRegisterFunctionCaching3()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->force_compile = true;
        $this->smarty->assign('x', 2);
        $this->smarty->assign('y', 30);
        $this->smarty->register_function('testfunction', 'myfunction', false);
        $this->assertEquals('hello world 2 30', $this->smarty->fetch('test_register_function.tpl'));
    } 
    public function testRegisterFunctionCaching4()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->assign('x', 3);
        $this->smarty->assign('y', 40);
        $this->smarty->register_function('testfunction', 'myfunction', false);
        $this->assertEquals('hello world 3 30', $this->smarty->fetch('test_register_function.tpl'));
    } 
    /**
    * test unregister_function method
    */
    public function testUnregisterFunction()
    {
        $this->smarty->register_function('testfunction', 'myfunction');
        $this->smarty->unregister_function('testfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testfunction']));
    } 
    /**
    * test unregister_function method not registered
    */
    public function testUnregisterFunctionNotRegistered()
    {
        $this->smarty->unregister_function('testfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testfunction']));
    } 
    /**
    * test unregister_function method other registered
    */
    public function testUnregisterFunctionOtherRegistered()
    {
        $this->smarty->register_block('testfunction', 'myfunction');
        $this->smarty->unregister_function('testfunction');
        $this->assertTrue(isset($this->smarty->registered_plugins['testfunction']));
    } 
} 
function myfunction($params, &$smarty)
{
    return "hello world $params[value]";
} 
class myfunctionclass {
    static function execute($params, &$smarty)
    {
        return "hello world $params[value]";
    } 
} 

?>
