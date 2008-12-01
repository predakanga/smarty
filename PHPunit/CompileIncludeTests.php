<?php
/**
* Smarty PHPunit tests compilation of the {include} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {include} tests
*/
class CompileIncludeTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test standard output
    */
    public function testInclude1()
    {
        $tpl = $this->smarty->createTemplate('string:{include file="helloworld.tpl"}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * Test that assign attribute does not create standard output
    */
    public function testInclude2()
    {
        $tpl = $this->smarty->createTemplate('string:{include file="helloworld.tpl" assign=foo}');
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
    /**
    * Test that assign attribute does load variable
    */
    public function testInclude3()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=bar}{include file="helloworld.tpl" assign=foo}{$foo}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * Test passing local vars
    */
    public function testInclude4()
    {
        $this->smarty->force_compile = false;
        $tpl = $this->smarty->createTemplate("string:{include file='string:{\$myvar1}{\$myvar2}' myvar1=1 myvar2=2}");
        $this->assertEquals("12", $this->smarty->fetch($tpl));
    } 
} 

?>
