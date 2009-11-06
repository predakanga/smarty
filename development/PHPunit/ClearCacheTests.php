<?php
/**
* Smarty PHPunit tests for clearing the cache
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for clearing the cache tests
*/
class ClearCacheTests extends PHPUnit_Framework_TestCase {
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
    * test clear_cache_all method
    */
    public function testClearCacheAll()
    {
        $this->smarty->clear_all_cache();
        file_put_contents($this->smarty->cache_dir . 'dummy.php', 'test');
        $this->assertEquals(1, $this->smarty->clear_all_cache());
    } 
    /**
    * test clear_cache_all method not expired
    */
    public function testClearCacheAllNotExpired()
    {
        file_put_contents($this->smarty->cache_dir . 'dummy.php', 'test');
        touch($this->smarty->cache_dir . 'dummy.php', time()-1000);
        $this->assertEquals(0, $this->smarty->clear_all_cache(2000));
    } 
    /**
    * test clear_cache_all method expired
    */
    public function testClearCacheAllExpired()
    {
        file_put_contents($this->smarty->cache_dir . 'dummy.php', 'test');
        touch($this->smarty->cache_dir . 'dummy.php', time()-1000);
        $this->assertEquals(1, $this->smarty->clear_all_cache(500));
    } 
} 

?>
