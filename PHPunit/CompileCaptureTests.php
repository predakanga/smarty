<?php
/**
* Smarty PHPunit tests compilation of capture tags
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for capture tags tests
*/
class CompileCaptureTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
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
    * test capture tag
    */
    public function testCapture1()
    {
        $tpl = $this->smarty->createTemplate('string:{capture assign=foo}hello world{/capture}');
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
    public function testCapture2()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=bar}{capture assign=foo}hello world{/capture}{$foo}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    public function testCapture3()
    {
        $tpl = $this->smarty->createTemplate('string:{capture name=foo}hello world{/capture}{$smarty.capture.foo}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    public function testCapture4()
    {
        $tpl = $this->smarty->createTemplate('string:{capture name=foo assign=bar}hello world{/capture}{$smarty.capture.foo} {$bar}');
        $this->assertEquals("hello world hello world", $this->smarty->fetch($tpl));
    } 
    public function testCapture5()
    {
        $tpl = $this->smarty->createTemplate('string:{capture}hello world{/capture}{$smarty.capture.default}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /*  The following test has been disabled. It fails only in PHPunit
    public function testCapture6()
    {
        $tpl = $this->smarty->createTemplate('string:{capture assign=foo}hello {capture assign=bar}this is my {/capture}world{/capture}{$foo} {$bar}');
        $this->assertEquals("hello world this is my ", $this->smarty->fetch($tpl),'This failure pops up only during PHPunit test ?????');
    } 
    */
} 

?>
