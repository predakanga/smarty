<?php
/**
* Smarty PHPunit tests compilation of assign tags
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for assign tags tests
*/
class CompileCaptureTests extends PHPUnit_Framework_TestCase {
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
    * test capture tag
    */

    public function testCapture1()
    {
        $tpl = $this->smarty->createTemplate("string:{capture assign=foo}hello world{/capture}");
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
    public function testCapture2()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=bar}{capture assign=foo}hello world{/capture}{\$foo}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
} 

?>
