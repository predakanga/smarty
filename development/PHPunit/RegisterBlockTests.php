<?php
/**
* Smarty PHPunit tests register_block / unregister_block methods
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for register_block / unregister_block methods tests
*/
class RegisterBlockTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = Smarty::instance();
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test register_block method for function
    */
    public function testRegisterBlockFunction()
    {
        $this->smarty->register_block('testblock', 'myblock');
        $this->assertEquals('myblock', $this->smarty->registered_plugins['testblock'][1]);
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
    } 
    /**
    * test register_block method for class
    */
    public function testRegisterBlockClass()
    {
        $this->smarty->register_block('testblock', array('myblockclass', 'execute'));
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
    } 
    /**
    * test register_block method for object
    */
    public function testRegisterBlockObject()
    {
        $myblock_object = new myblockclass;
        $this->smarty->register_block('testblock', array($myblock_object, 'execute'));
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
    } 
    /**
    * test unregister_block method
    */
    public function testUnregisterBlock()
    {
        $this->smarty->register_block('testblock', 'myblock');
        $this->smarty->unregister_block('testblock');
        $this->assertFalse(isset($this->smarty->registered_plugins['testblock']));
    } 
    /**
    * test unregister_block method not registered
    */
    public function testUnregisterBlockNotRegistered()
    {
        $this->smarty->unregister_block('testblock');
        $this->assertFalse(isset($this->smarty->registered_plugins['testblock']));
    } 
    /**
    * test unregister_block method other registerd
    */
    public function testUnregisterBlockOtherRegistered()
    {
        $this->smarty->register_function('testblock', 'myblock');
        $this->smarty->unregister_block('testblock');
        $this->assertTrue(isset($this->smarty->registered_plugins['testblock']));
    } 
} 
function myblock($params, $content, &$smarty_tpl, &$repeat)
{
    return "hello world";
} 

class myblockclass {
    function execute($params, $content, &$smarty_tpl, &$repeat)
    {
        return "hello world";
    } 
} 

?>
