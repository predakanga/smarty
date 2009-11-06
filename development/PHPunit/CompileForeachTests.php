<?php
/**
* Smarty PHPunit tests compilation of {foreach} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for {foreach} tag tests
*/
class CompileForeachTests extends PHPUnit_Framework_TestCase {
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
    /**
    * test {foreach $foo as $x} tag
    */
    public function testNewForeach1()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach $foo as $x}{$x}{/foreach}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach2()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach $foo as $x}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("0123456789", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach3()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach $foo as $x}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("else", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach4()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[9,8,7,6,5,4,3,2,1,0]}{foreach $foo as $x}{$x@key}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("09182736455463728190", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach5()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach $foo as $x}{$x}{foreachelse}else{/foreach}total{$x@total}');
        $this->assertEquals("0123456789total10", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach6()
    {
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value=[0,1,2,3,4,5,6,7,8,9]}{foreach $foo as $x}{$x@index}{$x@iteration}{foreachelse}else{/foreach}');
        $this->assertEquals("011223344556677889910", $this->smarty->fetch($tpl));
    } 
    public function testNewForeach7()
    {
        $tpl = $this->smarty->createTemplate('string:{foreach [9,8,7,6,5,4,3,2,1,0] as $x}{$x@key}{$x}{foreachelse}else{/foreach}');
        $this->assertEquals("09182736455463728190", $this->smarty->fetch($tpl));
    } 
} 

?>
