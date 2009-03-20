<?php
/**
* Smarty PHPunit tests for resources
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for resource tests
*/
class ResourceTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
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
    * test string resource
    */
    public function testStringResurce()
    {
        $this->assertEquals("hallo world", $this->smarty->fetch('string:hallo world'));
    } 
} 

?>
