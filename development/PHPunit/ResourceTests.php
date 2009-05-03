<?php
/**
* Smarty PHPunit tests for resources
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for resource tests
*/
class ResourceTests extends PHPUnit_Framework_TestCase {

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
    * test string resource
    */
    public function testStringResurce()
    {
        $this->assertEquals("hallo world", $this->smarty->fetch('string:hallo world'));
    } 
} 

?>
