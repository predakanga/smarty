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
    public function testExtendResource()
    {
        $this->smarty->force_compile=true;
        $this->smarty->assign ('foo', 'this is foo text');
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertContains('-- My titel --', $result);
        $this->assertContains('-- Yes we can --', $result);
        $this->assertContains(' assigned description this is foo text ', $result);
        $this->assertContains('this is an included parent from block_section', $result);
    } 
    /* Test create cache file */
    public function testExtendResource1()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->assign ('foo', 'this is foo text');
        $result = $this->smarty->fetch('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertContains('-- My titel --', $result);
        $this->assertContains('-- Yes we can --', $result);
        $this->assertContains(' assigned description this is foo text ', $result);
        $this->assertContains('this is an included parent from block_section', $result);
    } 
    /* Test access cache file */
    public function testExtendResource2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->assign ('foo', 'this is foo text');
        $tpl = $this->smarty->createTemplate('extend:test_block_base.tpl|test_block_section.tpl|test_block.tpl');
        $this->assertTrue($this->smarty->is_cached($tpl));
        $result = $this->smarty->fetch($tpl);
        $this->assertContains('-- My titel --', $result);
        $this->assertContains('-- Yes we can --', $result);
        $this->assertContains(' assigned description this is foo text ', $result);
        $this->assertContains('this is an included parent from block_section', $result);
    } 
} 

?>
