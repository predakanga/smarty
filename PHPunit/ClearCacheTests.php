<?php
/**
* Smarty PHPunit tests for clearing the cache
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for clearing the cache tests
*/
class ClearCacheTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test clear_cache_all method
    */
    public function testClearCacheAll()
    {
		$this->smarty->clear_all_cache();
		file_put_contents($this->smarty->cache_dir.'dummy.php', 'test');
		$this->assertEquals(1, $this->smarty->clear_all_cache());
    } 
} 

?>
