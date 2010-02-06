<?php
/**
* Smarty PHPunit tests register->compilerFunction / unregister->compilerFunction methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register->compilerFunction / unregister->compilerFunction methods tests
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
    * test register->compilerFunction method for function
    */
    public function testRegisterCompilerFunction()
    {                                   
        $this->smarty->register->compilerFunction('testcompilerfunction', 'mycompilerfunction');
        $this->assertEquals('mycompilerfunction', $this->smarty->registered_plugins['compiler']['testcompilerfunction'][0]);
    } 
    /**
    * test unregister->compilerFunction method
    */
    public function testUnregisterCompilerFunction()
    {
        $this->smarty->register->compilerFunction('testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregister->compilerFunction('testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testcompilerfunction']));
    } 
    /**
    * test unregister->compilerFunction method not registered
    */
    public function testUnregisterCompilerFunctionNotRegistered()
    {
        $this->smarty->unregister->compilerFunction('testcompilerfunction');
        $this->assertFalse(isset($this->smarty->registered_plugins['testcompilerfunction']));
    } 
    /**
    * test unregister->compilerFunction method other registered
    */
    public function testUnregisterCompilerFunctionOtherRegistered()
    {
        $this->smarty->register->block('testcompilerfunction', 'mycompilerfunction');
        $this->smarty->unregister->compilerFunction('testcompilerfunction');
        $this->assertTrue(isset($this->smarty->registered_plugins['block']['testcompilerfunction']));
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
