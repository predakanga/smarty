<?php
/**
* Smarty PHPunit tests load_filter method
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for load_filter method tests
*/
class LoadFilterTests extends PHPUnit_Framework_TestCase {
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
    * test load_filter method 
    */
    public function testLoadFilter()
    {
        $this->smarty->load_filter('output', 'trimwhitespace');
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['smarty_outputfilter_trimwhitespace']));
    } 
} 
?>
