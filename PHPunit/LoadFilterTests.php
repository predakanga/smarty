<?php
/**
* Smarty PHPunit tests load_filter method
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for load_filter method tests
*/
class LoadFilterTests extends PHPUnit_Framework_TestCase {
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
    * test load_filter method 
    */
    public function testLoadFilter()
    {
        $this->smarty->load_filter('output', 'trimwhitespace');
        $this->assertTrue(is_callable($this->smarty->registered_filters['output']['Smarty_outputfilter_trimwhitespace']));
    } 
} 
?>
