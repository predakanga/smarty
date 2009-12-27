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
        $this->assertEquals('./cache/91/20/51/91205107.helloworld.tpl.php', str_replace('\\','/',$tpl->getCachedFilepath()));
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
        $this->assertEquals('./cache/foo/bar/91/20/51/91205107.helloworld.tpl.php', str_replace('\\','/',$tpl->getCachedFilepath()));
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
        $this->assertEquals('./cache/blar/91/20/51/91205107.helloworld.tpl.php', str_replace('\\','/',$tpl->getCachedFilepath()));
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
        $this->assertEquals('./cache/foo/bar/blar/91/20/51/91205107.helloworld.tpl.php', str_replace('\\','/',$tpl->getCachedFilepath()));
    } 
    /**
    * test clear_cache_all with cache_id and compile_id
    */
    public function testClearCacheAllCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_all_cache());
    } 
    /**
    * test clear_cache with cache_id and compile_id
    */
    public function testClearCacheCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clear_all_cache();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null, 'foo|bar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
   } 
    public function testClearCacheCacheIdCompileIdSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clear_all_cache();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null, 'foo|bar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 

    public function testClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId2Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache('helloworld.tpl'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId3()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clear_all_cache();
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId3Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clear_all_cache();
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId4()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId4Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl', null, 'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId5()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId5Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',  'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null, null, 'blar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheFile()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->rendered_content = 'hello world';
        $tpl4->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
        $this->assertEquals(3, $this->smarty->clear_cache('helloworld.tpl'));
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
        $this->smarty->clear_all_cache();
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->rendered_content = 'hello world';
        $tpl4->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl4->getCachedFilepath()));
        $this->assertEquals(3, $this->smarty->clear_cache('helloworld.tpl'));
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
        $this->smarty->clear_compiled_tpl();
        $this->smarty->clear_all_cache();
    } 
} 

?>
