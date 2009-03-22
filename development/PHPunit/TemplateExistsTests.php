<?php
/**
* Smarty PHPunit tests for template_exists methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for template_exists tests
*/
class TemplateExistsTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
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
