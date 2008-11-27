<?php
/**
* Smarty PHPunit tests compilation of {eval} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {eval} tag tests
*/
class CompileEvalTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test eval tag
    */
    public function testEval1()
    {
        $tpl = $this->smarty->createTemplate("string:{eval var='hello world'}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    public function testEval2()
    {
        $tpl = $this->smarty->createTemplate("string:{eval var='hello world' assign=foo}{\$foo}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    public function testEval3()
    {
        $tpl = $this->smarty->createTemplate("string:{eval var='hello world' assign=foo}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
} 

?>
