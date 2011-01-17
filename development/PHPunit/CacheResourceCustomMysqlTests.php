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
class CacheResourceCustomMysqlTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->caching_type = 'mysqltest';
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
    } 

    public static function isRunnable()
    {
        return true;
    } 
    
    protected function doClearCacheAssertion($a, $b)
    {
        $this->assertEquals($a, $b);
    }

    /**
    * test getCachedFilepath with use_sub_dirs enabled
    */
    public function testGetCachedFilepathSubDirs()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
	    $sha1 = sha1('helloworld.tpl');
        $this->assertEquals($sha1, $tpl->cached->filepath);
    } 
    /**
    * test getCachedFilepath with cache_id
    */
    public function testGetCachedFilepathCacheId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar');
        $sha1 = sha1('helloworld.tpl' . 'foo|bar' . null);
        $this->assertEquals($sha1, $tpl->cached->filepath);
    } 
    /**
    * test getCachedFilepath with compile_id
    */
    public function testGetCachedFilepathCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'blar');
        $sha1 = sha1('helloworld.tpl' . null . 'blar');
        $this->assertEquals($sha1, $tpl->cached->filepath);
    } 
    /**
    * test getCachedFilepath with cache_id and compile_id
    */
    public function testGetCachedFilepathCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $sha1 = sha1('helloworld.tpl' . 'foo|bar' . 'blar');
        $this->assertEquals($sha1, $tpl->cached->filepath);
    } 
    /**
    * test cache->clear_all with cache_id and compile_id
    */
    public function testClearCacheAllCacheIdCompileId()
    {
        $this->smarty->clearAllCache();
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        // Custom CacheResources may return -1 if they can't tell the number of deleted elements
        $this->assertEquals(-1, $this->smarty->clearAllCache());
    } 
    /**
    * test cache->clear with cache_id and compile_id
    */
    public function testClearCacheCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(2, $this->smarty->clearCache(null, 'foo|bar'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertNull($tpl->cached->handler->get($tpl3));
    } 

    public function testClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(2, $this->smarty->clearCache('helloworld.tpl'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 

    public function testClearCacheCacheIdCompileId2Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar2', 'blar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(2, $this->smarty->clearCache('helloworld.tpl'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId3()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        // test that caches are deleted properly
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId3Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        // test that caches are deleted properly
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId4()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        // test that caches are deleted properly
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId4Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(1, $this->smarty->clearCache('helloworld.tpl', null, 'blar2'));
        // test that caches are deleted properly
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId5()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(2, $this->smarty->clearCache(null, null, 'blar'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertNull($tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheIdCompileId5Sub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl', 'foo|bar', 'blar');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',  'foo|bar', 'blar2');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', 'foo|bar', 'blar');
        $tpl3->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        // test number of deleted caches
        $this->doClearCacheAssertion(2, $this->smarty->clearCache(null, null, 'blar'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertNull($tpl->cached->handler->get($tpl3));
    } 
    public function testClearCacheCacheFile()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl4));
        // test number of deleted caches
        $this->doClearCacheAssertion(3, $this->smarty->clearCache('helloworld.tpl'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertNull($tpl->cached->handler->get($tpl3));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl4));
    } 
    public function testClearCacheCacheFileSub()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->clearAllCache();
        // create and cache templates
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $tpl->writeCachedContent('hello world');
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl',null,'bar');
        $tpl2->writeCachedContent('hello world');
        $tpl3 = $this->smarty->createTemplate('helloworld.tpl','buh|blar');
        $tpl3->writeCachedContent('hello world');
        $tpl4 = $this->smarty->createTemplate('helloworld2.tpl');
        $tpl4->writeCachedContent('hello world');
        // test cached content
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl2));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl3));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl4));
        // test number of deleted caches
        $this->doClearCacheAssertion(3, $this->smarty->clearCache('helloworld.tpl'));
        // test that caches are deleted properly
        $this->assertNull($tpl->cached->handler->get($tpl));
        $this->assertNull($tpl->cached->handler->get($tpl2));
        $this->assertNull($tpl->cached->handler->get($tpl3));
        $this->assertEquals('hello world', $tpl->cached->handler->get($tpl4));
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