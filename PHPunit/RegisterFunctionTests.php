<?php
/**
* Smarty PHPunit tests register_function / unregister_function methods
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for register_function / unregister_function methods tests
*/
class RegisterFunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
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
    * test register_function method for function
    */
    public function testRegisterFunction()
    {
        $this->smarty->register_function('testfunction', 'myfunction');
        $this->assertEquals('myfunction', $this->smarty->registered_plugins['testfunction'][1]);
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
    } 
    /**
    * test register_function method for class
    */
    public function testRegisterFunctionClass()
    {
        $this->smarty->register_function('testfunction', array('myfunctionclass', 'execute'));
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
    } 
    /**
    * test register_function method for object
    */
    public function testRegisterFunctionObject()
    {
        $myfunction_object = new myfunctionclass;
        $this->smarty->register_function('testfunction', array($myfunction_object, 'execute'));
        $this->assertEquals('function', $this->smarty->registered_plugins['testfunction'][0]);
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
    return "hello world";
} 
class myfunctionclass {
    function execute($params, &$smarty)
    {
        return "hello world";
    } 
} 

?>
