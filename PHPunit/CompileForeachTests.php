<?php
/**
* Smarty PHPunit tests compilation of {foreach} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {foreach} tag tests
*/
class CompileForeachTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
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
    * test {foreach} tag
    */
    public function testForeach1()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach item=x from=$foo}{$x}{/foreach}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testForeach2()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach item=x from=$foo}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testForeach3()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach item=x from=$foo}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("else", $this->smarty->fetch($tpl));
    } 
    public function testForeach4()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach item=x from=[0,1,2,3,4,5,6,7,8,9]}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testForeach5()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach item=x key=y from=[9,8,7,6,5,4,3,2,1,0]}{$y}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("09182736455463728190", $this->smarty->fetch($tpl));
    } 
    public function testForeach6()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach item=x name=foo from=[0,1,2,3,4,5,6,7,8,9]}{$x}{foreachelse}else{/foreach}total{$smarty.foreach.foo.total}');
        $this->assertEquals("0123456789total10", $this->smarty->fetch($tpl));
    } 
    public function testForeach7()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach item=x name=foo from=[0,1,2,3,4,5,6,7,8,9]}{$smarty.foreach.foo.index}{$smarty.foreach.foo.iteration}{foreachelse}else{/foreach}');
        $this->assertEquals("011223344556677889910", $this->smarty->fetch($tpl));
    } 
} 

?>
