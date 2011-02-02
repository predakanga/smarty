<?php
/**
* Smarty PHPunit tests for File resources
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for file resource tests
*/
class FileResourceTests extends PHPUnit_Framework_TestCase {
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
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('./templates/helloworld.tpl', str_replace('\\','/',$tpl->source->filepath));
    } 
    /**
    * test template file exits
    */
    public function testTemplateFileExists1()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($tpl->source->exists);
    } 
    public function testTemplateFileExists2()
    {
        $this->assertTrue($this->smarty->templateExists('helloworld.tpl'));
    } 
    /**
    * test template file is not existing
    */
    public function testTemplateFileNotExists1()
    {
        $tpl = $this->smarty->createTemplate('notthere.tpl');
        $this->assertFalse($tpl->source->exists);
    } 
    public function testTemplateFileNotExists2()
    {
        $this->assertFalse($this->smarty->templateExists('notthere.tpl'));
    } 
    public function testTemplateFileNotExists3()
    {
        try {
            $result = $this->smarty->fetch('notthere.tpl');
        } 
        catch (Exception $e) {
            $this->assertContains('Unable to load template file \'notthere.tpl\'', $e->getMessage());
            return;
        } 
        $this->fail('Exception for not existing template is missing');
    } 
    /**
    * test getTemplateTimestamp
    */
    public function testGetTemplateTimestamp()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue(is_integer($tpl->source->timestamp));
        $this->assertEquals(10, strlen($tpl->source->timestamp));
    } 
    /**
    * test getTemplateSource
    */
    public function testGetTemplateSource()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('hello world', $tpl->source->content);
    } 
    /**
    * test usesCompiler
    */
    public function testUsesCompiler()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->source->uncompiled);
    } 
    /**
    * test isEvaluated
    */
    public function testIsEvaluated()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->source->recompiled);
    } 
    /**
    * test getCompiledFilepath
    */
    public function testGetCompiledFilepath()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $expected = './templates_c/'.sha1($this->smarty->template_dir[0].'helloworld.tpl').'.file.helloworld.tpl.php';
        $this->assertEquals(realpath($expected), realpath($tpl->compiled->filepath));
    } 
    /**
    * test getCompiledTimestamp
    */
    public function testGetCompiledTimestampPrepare()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl'); 
        // create dummy compiled file
        file_put_contents($tpl->compiled->filepath, 'test');
        touch($tpl->compiled->filepath, $tpl->source->timestamp);
    } 
    public function testGetCompiledTimestamp()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl'); 
        $this->assertTrue(is_integer($tpl->compiled->timestamp));
        $this->assertEquals(10, strlen($tpl->compiled->timestamp));
        $this->assertEquals($tpl->compiled->timestamp, $tpl->source->timestamp);
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
        touch($tpl->source->filepath);
        // reset cache for this test to work
        unset($tpl->source->timestamp);
        $this->assertTrue($tpl->mustCompile()); 
        // clean up for next tests
        $this->smarty->clearCompiledTemplate();
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
        $this->assertTrue(file_exists($tpl->compiled->filepath));
    } 
    /**
    * test getCachedFilepath if caching disabled
    */
    public function testGetCachedFilepathCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->cached->filepath);
    } 
    /**
    * test getCachedFilepath
    */
    public function testGetCachedFilepath()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
	$expected = './cache/'.sha1($this->smarty->template_dir[0].'helloworld.tpl').'.helloworld.tpl.php';
        $this->assertEquals(realpath($expected), realpath($tpl->cached->filepath));
    } 
    /**
    * test getCachedTimestamp caching disabled
    */
    public function testGetCachedTimestampCachingDisabled()
    {
        // create dummy cache file for the following test
        file_put_contents('./cache/'.sha1($this->smarty->template_dir[0].'helloworld.tpl').'.helloworld.tpl.php', 'test');
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($tpl->cached->timestamp);
    } 
    /**
    * test getCachedTimestamp caching enabled
    */
    public function testGetCachedTimestamp()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue(is_integer($tpl->cached->timestamp));
        $this->assertEquals(10, strlen($tpl->cached->timestamp));
    } 
    /**
    * test prepare files for isCached test
    */
    public function testIsCachedPrepare()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        // clean up for next tests
        $this->smarty->clearCompiledTemplate();
	  $this->smarty->clearAllCache();
        // compile and cache
	  $this->smarty->fetch($tpl);
    } 
    /**
    * test isCached
    */
    public function testIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($tpl->isCached());
        $this->assertEquals(null, $tpl->rendered_content);
    } 
    /**
    * test isCached on touched source
    */
    public function testIsCachedTouchedSource()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        sleep(1);
        touch ($tpl->source->filepath);
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
        $this->smarty->cache_lifetime = 1000;
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
        $this->smarty->cache_lifetime = 1000;
	  $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->smarty->fetch($tpl);
        $this->assertTrue(file_exists($tpl->cached->filepath));
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
        $this->smarty->cache_lifetime = 1000;
        // clean up for next tests
        $this->smarty->clearCompiledTemplate();
	  $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->smarty->fetch($tpl);
    } 
    public function testSmartyIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertTrue($this->smarty->isCached($tpl));
        $this->assertEquals(null, $tpl->rendered_content);
    } 
    /**
    * test $smarty->is_cached  caching disabled
    */
    public function testSmartyIsCachedCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertFalse($this->smarty->isCached($tpl));
    } 
    /**
    * final cleanup
    */
    public function testFinalCleanup()
    {
        $this->smarty->clearCompiledTemplate();
	  $this->smarty->clearAllCache();
    } 
} 

?>
