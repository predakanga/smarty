<?php
/**
* Smarty PHPunit tests for File resources
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for file resource tests
*/
class FileResourceTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test getTemplateFilepath
    */
    public function testGetTemplateFilepath()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('.\templates\helloworld.tpl', $tpl->getTemplateFilepath());
    } 
    /**
    * test getTemplateTimestamp
    */
    public function testGetTemplateTimestamp()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue(is_integer($tpl->getTemplateTimestamp()));
        $this->assertEquals(10, strlen($tpl->getTemplateTimestamp()));
    } 
    /**
    * test getTemplateSource
    */
    public function testGetTemplateSource()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('hello world', $tpl->getTemplateSource());
    } 
    /**
    * test usesCompiler
    */
    public function testUsesCompiler()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($tpl->usesCompiler());
    } 
    /**
    * test isEvaluated
    */
    public function testIsEvaluated()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->isEvaluated());
    } 
    /**
    * test getCompiledFilepath
    */
    public function testGetCompiledFilepath()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('.\templates_c\2815259839.helloworld.tpl.php', $tpl->getCompiledFilepath());
    } 
    /**
    * test getCompiledTimestamp
    */
    public function testGetCompiledTimestamp()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl'); 
        // create dummy compiled file
        file_put_contents($tpl->getCompiledFilepath(), 'test');
        touch($tpl->getCompiledFilepath(), $tpl->getTemplateTimestamp());
        $this->assertTrue(is_integer($tpl->getCompiledTimestamp()));
        $this->assertEquals(10, strlen($tpl->getCompiledTimestamp()));
        $this->assertEquals($tpl->getCompiledTimestamp(), $tpl->getTemplateTimestamp());
    } 
    /**
    * test mustCompile if compiled template exists
    */
    public function testMustCompileExisting()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->mustCompile());
    } 
    /**
    * test mustCompile if force compile = true
    */
    public function testMustCompileAtForceCompile()
    {
        $this->smarty->force_compile = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($tpl->mustCompile());
    } 
    /**
    * test mustCompile on touched source file
    */
    public function testMustCompileTouchedSource()
    {
        $this->smarty->force_compile = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        touch($tpl->getTemplateFilepath());
        $this->assertTrue($tpl->mustCompile()); 
        // clean up for next tests
        $this->smarty->clear_compiled_tpl();
    } 
    /**
    * test getCompiledTemplate
    */
    public function testGetCompiledTemplate()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $result = $tpl->getCompiledTemplate();
        $this->assertContains('hello world', $result);
        $this->assertContains('<?php /* Smarty version ', $result);
    } 
    /**
    * test that compiled template file exists
    */
    public function testCompiledTemplateFileExits()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue(file_exists($tpl->getCompiledFilepath()));
    } 
    /**
    * test that timestamps are equal
    */
    public function testTimeStamps()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($tpl->getTemplateTimestamp()==$tpl->getCompiledTimestamp());
    } 
    /**
    * test getCachedFilepath if caching disabled
    */
    public function testGetCachedFilepathCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath
    */
    public function testGetCachedFilepath()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('.\cache\2815259839.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedTimestamp caching disabled
    */
    public function testGetCachedTimestampCachingDisabled()
    {
        // create dummy cache file for the following test
        file_put_contents('.\cache\2815259839.helloworld.tpl.php', 'test');
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->getCachedTimestamp());
    } 
    /**
    * test getCachedTimestamp caching enabled
    */
    public function testGetCachedTimestamp()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue(is_integer($tpl->getCachedTimestamp()));
        $this->assertEquals(10, strlen($tpl->getCachedTimestamp()));
    } 
    /**
    * test getCachedContent caching disabled
    */
    public function testGetCachedContentCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->getCachedContent());
    } 
    /**
    * test getCachedContent 
    */
    public function testGetCachedContent()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('test', $tpl->getCachedContent());
    } 
    /**
    * test prepare files for isCached test
    */
    public function testIsCachedPrepare()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        // clean up for next tests
        $this->smarty->clear_compiled_tpl();
	  $this->smarty->clear_all_cache();
        // compile and cache
	  $this->smarty->fetch($tpl);
    } 
    /**
    * test isCached
    */
    public function testIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->compileTemplateSource ();
        $this->assertTrue($tpl->isCached());
        $this->assertContains('hello world', $tpl->rendered_content);
    } 
    /**
    * test isCached on touched source
    */
    public function testIsCachedTouchedSource()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        sleep(1);
        touch ($tpl->getTemplateFilepath ());
        $this->assertFalse($tpl->isCached());
    } 
    /**
    * test isCached caching disabled
    */
    public function testIsCachedCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->isCached());
    } 
    /**
    * test isCached force compile
    */
    public function testIsCachedForceCompile()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->force_compile = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->isCached());
    } 
    /**
    * test is cache file is written
    */
    public function testWriteCachedContent()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
	  $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->smarty->fetch($tpl);
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
    } 
    /**
    * test getRenderedTemplate
    */
    public function testGetRenderedTemplate()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('hello world', $tpl->getRenderedTemplate());
    } 
    /**
    * test $smarty->is_cached
    */
    public function testSmartyIsCachedPrepare()
    {
        // prepare files for next test
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        // clean up for next tests
        $this->smarty->clear_compiled_tpl();
	  $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->smarty->fetch($tpl);
    } 
    public function testSmartyIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($this->smarty->is_cached($tpl));
        $this->assertContains('hello world', $tpl->rendered_content);
    } 
    /**
    * test $smarty->is_cached  caching disabled
    */
    public function testSmartyIsCachedCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($this->smarty->is_cached($tpl));
    } 
    /**
    * final cleanup
    */
    public function testFinalCleanup()
    {
        $this->smarty->clear_compiled_tpl();
	  $this->smarty->clear_all_cache();
    } 
} 

?>
