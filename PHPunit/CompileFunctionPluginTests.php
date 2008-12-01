<?php
/**
* Smarty PHPunit tests compilation of function plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for function plugin tests
*/
class CompileFunctionPluginTests extends PHPUnit_Framework_TestCase {
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
    * test function plugin tag
    */
    public function testFunctionPlugin1()
    {
        $tpl = $this->smarty->createTemplate('string:{counter start=10}{counter}');
        $this->assertEquals("1011", $this->smarty->fetch($tpl));
    } 
    public function testFunctionPlugin2()
    {
        $tpl = $this->smarty->createTemplate('string:{counter start=10 assign=foo}{counter}');
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
    public function testFunctionPlugin3()
    {
        $tpl = $this->smarty->createTemplate('string:{counter start=10 assign=foo}{counter}{$foo}',$this->smarty->tpl_vars);
        $this->assertEquals("11", $this->smarty->fetch($tpl));
    } 
} 

?>
