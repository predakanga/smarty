<?php
/**
* Smarty PHPunit tests compilation of {section} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {section} tag tests
*/
class CompileSectionTests extends PHPUnit_Framework_TestCase {
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
    * test {section} tag
    */
    public function testSection1()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{section name=bar loop=$foo}{$foo[bar]}{/section}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testSection2()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{section name=bar loop=$foo}{$foo[bar]}{sectionelse}else{/section}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testSection3()
    {
        $tpl = $this->smarty->createTemplate('string:{section name=bar loop=$foo}{$foo[bar]}{sectionelse}else{/section}');
        $this->assertEquals("else", $this->smarty->fetch($tpl));
    } 
    public function testSection4()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{section name=bar loop=$foo}{$foo[bar]}{sectionelse}else{/section}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testSection6()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{section name=bar loop=$foo}{$foo[bar]}{sectionelse}else{/section}total{$smarty.section.bar.total}');
        $this->assertEquals("0123456789total10", $this->smarty->fetch($tpl));
    } 
    public function testSection7()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{section name=bar loop=$foo}{$smarty.section.bar.index}{$smarty.section.bar.iteration}{sectionelse}else{/section}');
        $this->assertEquals("011223344556677889910", $this->smarty->fetch($tpl));
    } 
} 

?>
