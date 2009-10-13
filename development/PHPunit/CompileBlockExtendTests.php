<?php
/**
* Smarty PHPunit tests for Block Extend
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for block extend compiler tests
*/
class CompileBlockExtendTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test block default outout
    */
    public function testBlockDefault1()
    {
        $result = $this->smarty->fetch('string:{block name=test}-- block default --{/block name=test}');
        $this->assertEquals('-- block default --', $result);
    } 

    public function testBlockDefault2()
    {
        $result = $this->smarty->fetch('string:{block name=test}-- block default --{/block}');
        $this->assertEquals('-- block default --', $result);
    } 

    public function testBlockDefault3()
    {
        $this->smarty->assign ('foo', 'another');
        $result = $this->smarty->fetch('string:{block name=test}-- {$foo} block default --{/block}');
        $this->assertEquals('-- another block default --', $result);
    } 
    public function testUnmatchedNameError()
    {
        try {
            $this->smarty->fetch('string:{block name=test}-- block default --{/block name=none}');
        } 
        catch (Exception $e) {
            $this->assertContains('mismatching name attributes', $e->getMessage());
            return;
        } 
        $this->fail('Exception for not matching name attributes has not been raised.');
    } 
    /**
    * test just call of  base template, no blocks predefined
    */
    public function testCompileBlockBase()
    {
        $result = $this->smarty->fetch('test_block_base.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section false--', $result);
        $this->assertContains('--block passed by section false--', $result);
        $this->assertContains('--block root false--', $result);
        $this->assertContains('--block assigned false--', $result);
        $this->assertContains('--parent from section false--', $result);
        $this->assertContains('--base--', $result);
        $this->assertContains('--block include false--', $result);
    } 
    public function testCompileBlockSection()
    {
        $result = $this->smarty->fetch('test_block_section.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section ok--', $result);
        $this->assertContains('--block passed by section false--', $result);
        $this->assertContains('--block root false--', $result);
        $this->assertContains('--block assigned false--', $result);
        $this->assertContains('--section--', $result);
        $this->assertContains('--base--', $result);
        $this->assertContains('--block include false--', $result);
    } 
    public function testCompileBlockRoot()
    {
        $this->smarty->assign('foo', 'hallo');
        $result = $this->smarty->fetch('test_block.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section ok--', $result);
        $this->assertContains('--block passed by section ok--', $result);
        $this->assertContains('--block root ok--', $result);
        $this->assertContains('--assigned hallo--', $result);
        $this->assertContains('--parent from --section-- block--', $result);
        $this->assertContains('--parent from --base-- block--', $result);
        $this->assertContains('--block include ok--', $result);
    } 
} 

?>
