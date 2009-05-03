<?php
/**
* Smarty PHPunit tests compilation of {nocache} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for {nocache} tag tests
*/
class CompileNocacheTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = Smarty::instance();
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
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
