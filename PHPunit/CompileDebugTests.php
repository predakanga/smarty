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
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
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
