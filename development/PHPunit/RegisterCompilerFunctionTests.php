<?php
/**
* Smarty PHPunit tests register_compiler_function / unregister_compiler_function methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register_compiler_function / unregister_compiler_function methods tests
*/
class RegisterCompilerFunctionTests extends PHPUnit_Framework_TestCase {
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
    * test register_compiler_function method for function
    */
    public function testRegisterCompilerFunction()
    {                                   
        $this->smarty->register_compiler_function('testcompilerfunction', 'mycompilerfunction');
        $this->assertEquals('mycompilerfunction', $this->smarty->registered_plugins['testcompilerfunction'][1]);
        $this->assertEquals('compiler', $this->smarty->registered_plugins['testcompilerfunction'][0]);
    } 
    /**
    * test register_compiler_function method for class
    */
    public function testRegisterCompilerFunctionClass()
    {
        $this->smarty->register_compiler_function('testcompilerfunction', array('mycompilerfunctionclass', 'execute'));
        $this->assertEquals('compiler', $this->smarty->registered_plugins['testcompilerfunction'][0]);
    } 
    /**
    * test register_compiler_function method for object
    */
    public function testRegisterCompilerFunctionObject()
    {
        $mycompilerfunction_object = new mycompilerfunctionclass;
        $this->smarty->register_compiler_function('testcompilerfunction', array($mycompilerfunction_object, 'execute'));
        $this->assertEquals('compiler', $this->smarty->registered_plugins['testcompilerfunction'][0]);
    } 
    /**
    * test unregister_compiler_function method
    */
    public function testUnregisterCompilerFunction()
    {
        $this->smarty->register_compiler_function('testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregister_compiler_function('testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testcompilerfunction']));
    } 
    /**
    * test unregister_compiler_function method not registered
    */
    public function testUnregisterCompilerFunctionNotRegistered()
    {
        $this->smarty->unregister_compiler_function('testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testcompilerfunction']));
    } 
    /**
    * test unregister_compiler_function method other registered
    */
    public function testUnregisterCompilerFunctionOtherRegistered()
    {
        $this->smarty->register_block('testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregister_compiler_function('testcompilerfunction');
        $this->assertTrue(isset($this->smarty->registered_plugins['testcompilerfunction']));
    } 
} 
function mycompilerfunction($params, &$smarty)
{
    return "echo 'hello world'";
} 
class mycompilerfunctionclass {
    static function execute($params, &$smarty)
    {
        return "echo 'hello world'";
    } 
} 

?>
