<?php
/**
* Smarty PHPunit tests compilation of {debug} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {debug} tag tests
*/
class CompileDebugTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
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
    * test debug tag
    */
    public function testDebug1()
    {
        $tpl = $this->smarty->createTemplate("string:{debug}");
        $_contents = $this->smarty->fetch($tpl);
        $this->assertContains("Smarty Debug Console", $_contents);
    } 
} 

?>
