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
        $this->smarty->deprecation_notices = false;
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
        $this->assertEquals(realpath($expected), realpath($tpl->cached->filepath));
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
        $this->assertEquals(realpath($expected), realpath($tpl->cached->filepath));
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
        $this->assertEquals(realpath($expected), realpath($tpl->cached->filepath));
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
        $this->assertEquals(realpath($expected), realpath($tpl->cached->filepath));
    } 
    /**
    * test cache->clear_all with cache_id and compile_id
    */
    public function testClearCacheAllCacheIdCompileId()
    {
        $this->smarty->clearAllCache();
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertEquals(1, $this->smarty->clearAllCache());
    } 
    /**
    * test cache->clear with cache_id and compile_id
    */
    public function testClearCacheCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(2, $this->smarty->clearCache(null, 'foo|bar'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
    } 
    public function testSmarty2ClearCacheCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->smarty->clear_cache(null, 'foo|bar');
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
    } 

    public function testSmarty2ClearCacheCacheIdCompileIdSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->smarty->clear_cache(null, 'foo|bar');
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
    } 

    public function testClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(2, $this->smarty->clearCache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testSmarty2ClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->smarty->clear_cache('helloworld.tpl');
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 

    public function testClearCacheCacheIdCompileId2Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(2, $this->smarty->clearCache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId3()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId3Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId4()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId4Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId5()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(2, $this->smarty->clearCache(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheIdCompileId5Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',  'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertEquals(2, $this->smarty->clearCache(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
    } 
    public function testClearCacheCacheFile()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertTrue(file_exists($tpl4->cached->filepath));
        $this->assertEquals(3, $this->smarty->clearCache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
        $this->assertTrue(file_exists($tpl4->cached->filepath));
    } 
    public function testClearCacheCacheFileSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clearAllCache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        $this->assertTrue(file_exists($tpl->cached->filepath));
        $this->assertTrue(file_exists($tpl2->cached->filepath));
        $this->assertTrue(file_exists($tpl3->cached->filepath));
        $this->assertTrue(file_exists($tpl4->cached->filepath));
        $this->assertEquals(3, $this->smarty->clearCache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->cached->filepath));
        $this->assertFalse(file_exists($tpl2->cached->filepath));
        $this->assertFalse(file_exists($tpl3->cached->filepath));
        $this->assertTrue(file_exists($tpl4->cached->filepath));
    } 
    /**
    * final cleanup
    */
    public function testFinalCleanup2()
    {
        $this->smarty->clearCompiledTemplate();
        $this->smarty->clearAllCache();
    } 
} 

?>