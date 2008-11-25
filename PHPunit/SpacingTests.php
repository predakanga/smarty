<?php
/**
* Smarty PHPunit tests spacimg in template output
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for security test
*/
class SpacingTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->force_compile = true;
        $this->smarty->assign('foo','bar');
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test variable output
    */
    public function testVariableSpacing1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo}",$this->smarty->tpl_vars);
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 
    public function testVariableSpacing2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo}{\$foo}",$this->smarty->tpl_vars);
        $this->assertEquals("barbar", $this->smarty->fetch($tpl));
    } 
    public function testVariableSpacing3()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo} {\$foo}",$this->smarty->tpl_vars);
        $this->assertEquals("bar bar", $this->smarty->fetch($tpl));
    } 

    /**
    * test variable text combinations
    */
    public function testVariableText1()
    {
        $tpl = $this->smarty->createTemplate("string:A{\$foo}B",$this->smarty->tpl_vars);
        $this->assertEquals("AbarB", $this->smarty->fetch($tpl));
    } 
    public function testVariableText2()
    {
        $tpl = $this->smarty->createTemplate("string:A {\$foo}B",$this->smarty->tpl_vars);
        $this->assertEquals("A barB", $this->smarty->fetch($tpl));
    } 
    public function testVariableText3()
    {
        $tpl = $this->smarty->createTemplate("string:A{\$foo} B",$this->smarty->tpl_vars);
        $this->assertEquals("Abar B", $this->smarty->fetch($tpl));
    } 
    public function testVariableText4()
    {
        $tpl = $this->smarty->createTemplate("string:A{\$foo}\nB",$this->smarty->tpl_vars);
        $this->assertEquals("AbarB", $this->smarty->fetch($tpl));
    } 
    public function testVariableText5()
    {
        $tpl = $this->smarty->createTemplate("string:A{\$foo}B\nC",$this->smarty->tpl_vars);
        $this->assertEquals("AbarB\nC", $this->smarty->fetch($tpl));
    } 

    /**
    * test tag text combinations
    */
    public function testTagText1()
    {
        $tpl = $this->smarty->createTemplate("string:A{assign var=zoo value='blah'}B");
        $this->assertEquals("AB", $this->smarty->fetch($tpl));
    } 
    public function testTagText2()
    {
        $tpl = $this->smarty->createTemplate("string:A\n{assign var=zoo value='blah'}\nB");
        $this->assertEquals("A\nB", $this->smarty->fetch($tpl));
    } 
    public function testTagText3()
    {
        $tpl = $this->smarty->createTemplate("string:E{assign var=zoo value='blah'}\nF");
        $this->assertEquals("EF", $this->smarty->fetch($tpl));
    } 
    public function testTagText4()
    {
        $tpl = $this->smarty->createTemplate("string:G\n{assign var=zoo value='blah'}H");
        $this->assertEquals("G\nH", $this->smarty->fetch($tpl));
    } 

} 

?>
