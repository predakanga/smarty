<?php
/**
* Smarty PHPunit tests for cacher resource file
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for cacher resource file tests
*/
class CacherResourceFileTests extends PHPUnit_Framework_TestCase {
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
    * test getCachedFilepath with use_sub_dirs enabled
    */
    public function testGetCachedFilepathSubDirs()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl');
        $this->assertEquals('.\cache\28\15\25\2815259839.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath with cache_id
    */
    public function testGetCachedFilepathCacheId()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar');
        $this->assertEquals('.\cache\foo\bar\28\15\25\2815259839.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath with compile_id
    */
    public function testGetCachedFilepathCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, null, 'blar');
        $this->assertEquals('.\cache\blar\28\15\25\2815259839.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath with cache_id and compile_id
    */
    public function testGetCachedFilepathCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $this->assertEquals('.\cache\foo\bar\blar\28\15\25\2815259839.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test clear_cache_all with cache_id and compile_id
    */
    public function testClearCacheAllCacheIdCompileId()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
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
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null,'foo|bar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId2()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar2', 'blar');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'foo|bar', 'blar');
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
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl',null,'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId4()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = false;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_cache('helloworld.tpl',null,'blar2'));
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
    } 
    public function testClearCacheCacheIdCompileId5()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $tpl->rendered_content = 'hello world';
        $tpl->writeCachedContent();
        $tpl2 = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar2');
        $tpl2->rendered_content = 'hello world';
        $tpl2->writeCachedContent();
        $tpl3 = $this->smarty->createTemplate('helloworld2.tpl', null, 'foo|bar', 'blar');
        $tpl3->rendered_content = 'hello world';
        $tpl3->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl3->getCachedFilepath()));
        $this->assertEquals(2, $this->smarty->clear_cache(null,null,'blar'));
        $this->assertFalse(file_exists($tpl->getCachedFilepath()));
        $this->assertTrue(file_exists($tpl2->getCachedFilepath()));
        $this->assertFalse(file_exists($tpl3->getCachedFilepath()));
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
