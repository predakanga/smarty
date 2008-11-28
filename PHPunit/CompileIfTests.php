<?php
/**
* Smarty PHPunit tests compilation of {if} tag
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for {if} tag tests
*/
class CompileIfTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test {if} tag
    */
    public function testIf1()
    {
        $tpl = $this->smarty->createTemplate('string:{if 0<1}yes{/if}');
        $this->assertEquals("yes", $this->smarty->fetch($tpl));
    } 
    public function testIf2()
    {
        $tpl = $this->smarty->createTemplate('string:{if 2<1}yes{else}no{/if}');
        $this->assertEquals("no", $this->smarty->fetch($tpl));
    } 
} 

?>
