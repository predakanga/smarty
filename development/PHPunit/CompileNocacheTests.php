<?php
/**
* Smarty PHPunit tests compilation of {nocache} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for {nocache} tag tests
*/
class CompileNocacheTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = false;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test nocache tag caching disabled
    */
    public function testNocacheCachingNo()
    {
        $this->smarty->caching = 0;
        $this->smarty->assign('foo', 0);
        $this->smarty->assign('bar', 'A');
        $this->assertEquals("2A", $this->smarty->fetch('test_nocache_tag.tpl'));
        $this->smarty->assign('foo', 2);
        $this->smarty->assign('bar', 'B');
        $this->assertEquals("4B", $this->smarty->fetch('test_nocache_tag.tpl'));
    } 
    /**
    * test nocache tag caching enabled
    */
    public function testNocacheCachingYes1()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 5;
        $this->smarty->assign('foo', 0);
        $this->smarty->assign('bar', 'A');
        $this->assertEquals("2A", $this->smarty->fetch('test_nocache_tag.tpl'));
    } 
    public function testNocacheCachingYes2()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 5;

        $this->smarty->assign('foo', 2);
        $this->smarty->assign('bar', 'B');
        $this->assertEquals("4A", $this->smarty->fetch('test_nocache_tag.tpl'));
    } 
} 

?>
