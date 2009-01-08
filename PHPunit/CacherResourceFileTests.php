<?php
/**
* Smarty PHPunit tests for cacher resource file
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for cacher resource file tests
*/
class CacherResourceFileTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
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
        $this->assertEquals('.\cache\53\a0\05\53a0059e50aff6f0cf3647bc28c42cb2.helloworld.tpl.php', $tpl->getCachedFilepath());
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
        $this->assertEquals('.\cache\foo\bar\53\a0\05\53a0059e50aff6f0cf3647bc28c42cb2.helloworld.tpl.php', $tpl->getCachedFilepath());
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
        $this->assertEquals('.\cache\blar\53\a0\05\53a0059e50aff6f0cf3647bc28c42cb2.helloworld.tpl.php', $tpl->getCachedFilepath());
    } 
    /**
    * test getCachedFilepath with cache_id and compile_id
    */
    public function testGetCachedFilepathCacheIdCompileIg()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 1000;
        $this->smarty->use_sub_dirs = true;
        $tpl = $this->smarty->createTemplate('helloworld.tpl', null, 'foo|bar', 'blar');
        $this->assertEquals('.\cache\foo\bar\blar\53\a0\05\53a0059e50aff6f0cf3647bc28c42cb2.helloworld.tpl.php', $tpl->getCachedFilepath());
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
        $tpl->cached_content = 'hello world';
        $tpl->writeCachedContent();
        $this->assertTrue(file_exists($tpl->getCachedFilepath()));
        $this->assertEquals(1, $this->smarty->clear_all_cache());
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
