<?php
/**
* Smarty PHPunit tests for PHP resources
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for PHP resource tests
*/
class PhpResourceTests extends PHPUnit_Framework_TestCase {
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
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertEquals('.\templates\phphelloworld.php', $tpl->getTemplateFilepath());
    } 
    /**
    * test getTemplateTimestamp
    */
    public function testGetTemplateTimestamp()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertTrue(is_integer($tpl->getTemplateTimestamp()));
        $this->assertEquals(10, strlen($tpl->getTemplateTimestamp()));
    } 
    /**
    * test getTemplateSource
    */
    public function testGetTemplateSource()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertContains('php hello world', $tpl->getTemplateSource());
    } 
    /**
    * test usesCompiler
    */
    public function testUsesCompiler()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->usesCompiler());
    } 
    /**
    * test isEvaluated
    */
    public function testIsEvaluated()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->isEvaluated());
    } 
    /**
    * test getCompiledFilepath
    */
    public function testGetCompiledFilepath()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->getCompiledFilepath());
    } 
    /**
    * test getCompiledTimestamp
    */
    public function testGetCompiledTimestamp()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php'); 
        $this->assertFalse($tpl->getCompiledTimestamp());
    } 
    /**
    * test mustCompile
    */
    public function testMustCompile()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->mustCompile());
    } 
    /**
    * test getCompiledTemplate
    */
    public function testGetCompiledTemplate()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->getCompiledTemplate());
    } 
    /**
    * test getCachedFilepath if caching disabled
    */
    public function testGetCachedFilepathCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath
    */
    public function testGetCachedFilepath()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertEquals('.\cache\d8754729f921c019636c61b214eb4881.phphelloworld.php.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedTimestamp caching disabled
    */
    public function testGetCachedTimestampCachingDisabled()
    {
        // create dummy cache file
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        file_put_contents('.\cache\d8754729f921c019636c61b214eb4881.phphelloworld.php.php', 'test');
        $this->assertFalse($tpl->getCachedTimestamp());
    } 
    /**
    * test getCachedTimestamp caching enabled
    */
    public function testGetCachedTimestamp()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertTrue(is_integer($tpl->getCachedTimestamp()));
        $this->assertEquals(10, strlen($tpl->getCachedTimestamp()));
    } 
    /**
    * test getCachedContent caching disabled
    */
    public function testGetCachedContentCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->getCachedContent());
    } 
    /**
    * test getCachedContent 
    */
    public function testGetCachedContent()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertEquals('test', $tpl->getCachedContent());
    } 
    /**
    * test isCached
    */
    public function testIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertTrue($tpl->isCached());
        $this->assertEquals('test', $tpl->rendered_content);
    } 
    /**
    * test isCached caching disabled
    */
    public function testIsCachedCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($tpl->isCached());
    } 
    /**
    * test isCached on touched source
    */
    public function testIsCachedTouchedSource()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        touch ($tpl->getCachedFilepath (),time()-2);
        touch ($tpl->getTemplateFilepath ());
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
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->smarty->fetch($tpl);
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
    } 
    /**
    * test getRenderedTemplate
    */
    public function testGetRenderedTemplate()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertContains('php hello world', $tpl->getRenderedTemplate());
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
	  $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->smarty->fetch($tpl);
    } 
    public function testSmartyIsCached()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertTrue($this->smarty->is_cached($tpl));
        $this->assertContains('php hello world', $tpl->rendered_content);
    } 
    /**
    * test $smarty->is_cached  caching disabled
    */
    public function testSmartyIsCachedCachingDisabled()
    {
        $tpl = $this->smarty->createTemplate('php:phphelloworld.php');
        $this->assertFalse($this->smarty->is_cached($tpl));
    } 
    /**
    * final cleanup
    */
    public function testFinalCleanup()
    {
	  $this->smarty->clear_all_cache();
    } 
} 

?>
