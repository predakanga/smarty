<?php
/**
* Smarty PHPunit tests variable output with nocache attribute
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for variable output with nocache attribute tag tests
*/
class PrintNocacheTests extends PHPUnit_Framework_TestCase {
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
    * test print nocache caching disabled
    */
    public function testPrintNocacheCachingNo1()
    {
        $this->smarty->caching = 0;
        $this->smarty->assign('foo', 0);
        $this->smarty->assign('bar', 'A');
        $this->assertEquals("0A", $this->smarty->fetch('test_print_nocache.tpl'));
    } 
    public function testPrintNocacheCachingNo2()
    {
        $this->smarty->caching = 0;
        $this->smarty->assign('foo', 2);
        $this->smarty->assign('bar', 'B');
        $this->assertEquals("2B", $this->smarty->fetch('test_print_nocache.tpl'));
    } 
    /**
    * test print nocache caching enabled
    */
    public function testPrintNocacheCachingYes1()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 5;
        $this->smarty->assign('foo', 0);
        $this->smarty->assign('bar', 'A');
        $this->assertEquals("0A", $this->smarty->fetch('test_print_nocache.tpl'));
    } 
    public function testPrintNocacheCachingYes2()
    {
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 5;

        $this->smarty->assign('foo', 2);
        $this->smarty->assign('bar', 'B');
        $this->assertEquals("2A", $this->smarty->fetch('test_print_nocache.tpl'));
    } 
} 

?>
