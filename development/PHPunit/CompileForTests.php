<?php
/**
* Smarty PHPunit tests compilation of {for} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for {for} tag tests
*/
class CompileForTests extends PHPUnit_Framework_TestCase {
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
    * test {for $x=0;$x<10;$x++} tag
    */
    public function testFor1()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=0;$x<10;$x++}{$x}{/for}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testFor2()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=0; $x<10; $x++}{$x}{forelse}else{/for}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testFor3()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=10;$x<10;$x++}{$x}{forelse}else{/for}');
        $this->assertEquals("else", $this->smarty->fetch($tpl));
    } 
    public function testFor4()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=9;$x>=0;$x--}{$x}{forelse}else{/for}');
        $this->assertEquals("9876543210", $this->smarty->fetch($tpl));
    } 
    public function testFor5()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=-1;$x>=0;$x--}{$x}{forelse}else{/for}');
        $this->assertEquals("else", $this->smarty->fetch($tpl));
    } 
    public function testFor6()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=0,$y=10;$x<$y;$x++}{$x}{forelse}else{/for}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testFor7()
    {
        $tpl = $this->smarty->createTemplate('string:{for $x=0;$x<10;$x=$x+2}{$x}{/for}');
        $this->assertEquals("02468", $this->smarty->fetch($tpl));
    } 
} 

?>
