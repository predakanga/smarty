<?php
/**
* Smarty PHPunit tests for cache resource file
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for cache resource file tests
*/
class CacheResourceFileTests extends PHPUnit_Framework_TestCase {
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
    * test getCachedFilepath with use_sub_dirs enabled
    */
    public function testGetCachedFilepathSubDirs()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
	$sha1 = sha1($this->smarty->template_dir[0].'helloworld.tpl');
	$expected = sprintf('./cache/%s/%s/%s/%s.helloworld.tpl.php',
			    substr($sha1, 0, 2),
			    substr($sha1, 2, 2),
			    substr($sha1, 4, 2),
			    $sha1
			    );
        $this->assertEquals(realpath($expected), realpath($tpl->getCachedFilepath()));
    } 
    /**
    * test getCachedFilepath with cache_id
    */
    public function testGetCachedFilepathCacheId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar');
	$sha1 = sha1($this->smarty->template_dir[0].'helloworld.tpl');
	$expected = sprintf('./cache/foo/bar/%s/%s/%s/%s.helloworld.tpl.php',
			    substr($sha1, 0, 2),
			    substr($sha1, 2, 2),
			    substr($sha1, 4, 2),
			    $sha1
			    );
        $this->assertEquals(realpath($expected), realpath($tpl->getCachedFilepath()));
    } 
    /**
    * test getCachedFilepath with compile_id
    */
    public function testGetCachedFilepathCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'blar');
	$sha1 = sha1($this->smarty->template_dir[0].'helloworld.tpl');
	$expected = sprintf('./cache/blar/%s/%s/%s/%s.helloworld.tpl.php',
			    substr($sha1, 0, 2),
			    substr($sha1, 2, 2),
			    substr($sha1, 4, 2),
			    $sha1
			    );
        $this->assertEquals(realpath($expected), realpath($tpl->getCachedFilepath()));
    } 
    /**
    * test getCachedFilepath with cache_id and compile_id
    */
    public function testGetCachedFilepathCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
	$sha1 = sha1($this->smarty->template_dir[0].'helloworld.tpl');
	$expected = sprintf('./cache/foo/bar/blar/%s/%s/%s/%s.helloworld.tpl.php',
			    substr($sha1, 0, 2),
			    substr($sha1, 2, 2),
			    substr($sha1, 4, 2),
			    $sha1
			    );
        $this->assertEquals(realpath($expected), realpath($tpl->getCachedFilepath()));
    } 
    /**
    * test cache->clear_all with cache_id and compile_id
    */
    public function testClearCacheAllCacheIdCompileId()
    {
        $this->smarty->cache->clearAll();
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->cache->clearAll());
    } 
    /**
    * test cache->clear with cache_id and compile_id
    */
    public function testClearCacheCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->cache->clearAll();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear(null, 'foo|bar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
   } 
    public function testClearCacheCacheIdCompileIdSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->cache->clearAll();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear(null, 'foo|bar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 

    public function testClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId2Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId3()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->cache->clearAll();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->cache->clear('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId3Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->cache->clearAll();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->cache->clear('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId4()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->cache->clear('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId4Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->cache->clear('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId5()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId5Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',  'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->cache->clear(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheFile()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
        $this->assertEquals(3, $this->smarty->cache->clear('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
    } 
    public function testClearCacheCacheFileSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->cache->clearAll();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
        $this->assertEquals(3, $this->smarty->cache->clear('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
    } 
    /**
    * final cleanup
    */
    public function testFinalCleanup2()
    {
        $this->smarty->utility->clearCompiledTemplate();
        $this->smarty->cache->clearAll();
    } 
} 

?>