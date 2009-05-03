<?php
/**
* Smarty PHPunit tests for template_exists methode
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for template_exists tests
*/
class TemplateExistsTests extends PHPUnit_Framework_TestCase {
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
    * test $smarty->template_exists true
    */
    public function testSmartyTemplateExists()
    {
        $this->assertTrue($this->smarty->template_exists('helloworld.tpl'));
    } 
    /**
    * test $smarty->template_exists false
    */
    public function testSmartyTemplateNotExists()
    {
        $this->assertFalse($this->smarty->template_exists('notthere.tpl'));
    } 
} 

?>
