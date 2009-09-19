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
        $this->smarty = SmartyTests::$smarty;
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
        $this->smarty->assign('value', 1);
        $this->assertEquals('myblock', $this->smarty->registered_plugins['testblock'][1]);
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
        $this->assertEquals('hello world 1', $this->smarty->fetch('string:{testblock}hello world {$value}{/testblock}'));
    } 
    /**
    * test register_block method for class
    */
    public function testRegisterBlockClass()
    {
        $this->smarty->register_block('testblock', array('myblockclass', 'execute'));
        $this->smarty->assign('value', 2);
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
        $this->assertEquals('hello world 2', $this->smarty->fetch('string:{testblock}hello world {$value}{/testblock}'));
    } 
    /**
    * test register_block method for object
    */
    public function testRegisterBlockObject()
    {
        $myblock_object = new myblockclass;
        $this->smarty->register_block('testblock', array($myblock_object, 'execute'));
        $this->smarty->assign('value', 3);
        $this->assertEquals('block', $this->smarty->registered_plugins['testblock'][0]);
        $this->assertEquals('hello world 3', $this->smarty->fetch('string:{testblock}hello world {$value}{/testblock}'));
    } 
    public function testRegisterBlockCaching1()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->force_compile = true;
        $this->smarty->assign('x', 1);
        $this->smarty->assign('y', 10);
        $this->smarty->assign('z', 100);
        $this->smarty->register_block('testblock', 'myblock');
        $this->assertEquals('1 10 100', $this->smarty->fetch('test_register_block.tpl'));
    } 
    public function testRegisterBlockCaching2()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->assign('x', 2);
        $this->smarty->assign('y', 20);
        $this->smarty->assign('z', 200);
        $this->smarty->register_block('testblock', 'myblock');
        $this->assertEquals('1 10 100', $this->smarty->fetch('test_register_block.tpl'));
    } 
    public function testRegisterBlockCaching3()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->force_compile = true;
        $this->smarty->assign('x', 3);
        $this->smarty->assign('y', 30);
        $this->smarty->assign('z', 300);
        $this->smarty->register_block('testblock', 'myblock', false);
        $this->assertEquals('3 30 300', $this->smarty->fetch('test_register_block.tpl'));
    } 
    public function testRegisterBlockCaching4()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $this->smarty->assign('x', 4);
        $this->smarty->assign('y', 40);
        $this->smarty->assign('z', 400);
        $this->smarty->register_block('testblock', 'myblock', false);
        $this->assertEquals('3 40 300', $this->smarty->fetch('test_register_block.tpl'));
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
    return $content;
} 

class myblockclass {
    static function execute($params, $content, &$smarty_tpl, &$repeat)
    {
        return $content;
    } 
} 

?>
