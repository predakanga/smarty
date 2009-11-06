<?php
/**
* Smarty PHPunit tests for Extendresource
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for extend resource tests
*/
class ExtendResourceTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /* Test compilation */
        public function testExtendResourceBlockBase()
    {
        $this->smarty->force_compile=true;
        $result = $this->smarty->fetch('extend:test_block_base.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section false--', $result);
        $this->assertContains('--block passed by section false--', $result);
        $this->assertContains('--block root false--', $result);
        $this->assertContains('--block assigned false--', $result);
        $this->assertContains('--parent from section false--', $result);
        $this->assertContains('--base--', $result);
        $this->assertContains('--block include false--', $result);
    } 
    public function testExtendResourceBlockSection()
    {
        $this->smarty->force_compile=true;
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section ok--', $result);
        $this->assertContains('--block passed by section false--', $result);
        $this->assertContains('--block root false--', $result);
        $this->assertContains('--block assigned false--', $result);
        $this->assertContains('--section--', $result);
        $this->assertContains('--base--', $result);
        $this->assertContains('--block include false--', $result);
    } 
    public function testExtendResourceBlockRoot()
    {
        $this->smarty->force_compile=true;
        $this->smarty->assign('foo', 'hallo');
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section ok--', $result);
        $this->assertContains('--block passed by section ok--', $result);
        $this->assertContains('--block root ok--', $result);
        $this->assertContains('--assigned hallo--', $result);
        $this->assertContains('--parent from --section-- block--', $result);
        $this->assertContains('--parent from --base-- block--', $result);
        $this->assertContains('--block include ok--', $result);
    } 

    /* Test create cache file */
    public function testExtendResource1()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->assign('foo', 'hallo');
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertContains('--block base ok--', $result);
        $this->assertContains('--block section ok--', $result);
        $this->assertContains('--block passed by section ok--', $result);
        $this->assertContains('--block root ok--', $result);
        $this->assertContains('--assigned hallo--', $result);
        $this->assertContains('--parent from --section-- block--', $result);
        $this->assertContains('--parent from --base-- block--', $result);
        $this->assertContains('--block include ok--', $result);
    } 
    /* Test access cache file */
    public function testExtendResource2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->assign('foo', 'world');
        $tpl = $this->smarty->createTemplate('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertTrue($this->smarty->is_cached($tpl));
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
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
