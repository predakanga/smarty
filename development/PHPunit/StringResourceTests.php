<?php
/**
* Smarty PHPunit tests for string resources
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for string resource tests
*/
class StringResourceTests extends PHPUnit_Framework_TestCase {
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
    * test getTemplateFilepath
    */
    public function testGetTemplateFilepath()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('string', $tpl->getTemplateFilepath());
    } 
    /**
    * test getTemplateTimestamp
    */
    public function testGetTemplateTimestamp()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getTemplateTimestamp());
    } 
    /**
    * test getTemplateSource
    */
    public function testGetTemplateSource()
    {
        $tpl = $this->smarty->createTemplate('string:hello world{$foo}');
        $this->assertEquals('hello world{$foo}', $tpl->getTemplateSource());
    } 
    /**
    * test usesCompiler
    */
    public function testUsesCompiler()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertTrue($tpl->usesCompiler());
    } 
    /**
    * test isEvaluated
    */
    public function testIsEvaluated()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertTrue($tpl->isEvaluated());
    } 
    /**
    * test mustCompile
    */
    public function testMustCompile()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertTrue($tpl->mustCompile());
    } 
    /**
    * test getCompiledFilepath
    */
    public function testGetCompiledFilepath()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getCompiledFilepath());
    } 
    /**
    * test getCompiledTimestamp
    */
    public function testGetCompiledTimestamp()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getCompiledTimestamp());
    } 
    /**
    * test getCompiledTemplate
    */
    public function testGetCompiledTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $result = $tpl->getCompiledTemplate();
        $this->assertContains('hello world', $result);
        $this->assertContains('<?php /* Smarty version ', $result);
    } 
    /**
    * test getCachedFilepath
    */
    public function testGetCachedFilepath()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getCachedFilepath());
    } 
    /**
    * test getCachedTimestamp
    */
    public function testGetCachedTimestamp()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getCachedTimestamp());
    } 
    /**
    * test getCachedContent
    */
    public function testGetCachedContent()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->getCachedContent());
    } 
    /**
    * test writeCachedContent
    */
    public function testWriteCachedContent()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->writeCachedContent());
    } 
    /**
    * test isCached
    */
    public function testIsCached()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->isCached());
    } 
    /**
    * test getRenderedTemplate
    */
    public function testGetRenderedTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('hello world', $tpl->getRenderedTemplate());
    } 
    /**
    * test that no complied template and cache file was produced
    */
    public function testNoFiles()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 20;
        $this->smarty->clear_compiled_tpl();
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
        $this->assertEquals(0, $this->smarty->clear_all_cache());
        $this->assertEquals(0, $this->smarty->clear_compiled_tpl());
    } 
    /**
    * test $smarty->is_cached
    */
    public function testSmartyIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 20;
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('hello world', $this->smarty->fetch($tpl));
        $this->assertFalse($this->smarty->is_cached($tpl));
    } 
} 

?>
