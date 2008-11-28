<?php
/**
* Smarty PHPunit tests compilation of block plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for block plugin tests
*/
class CompileBlockPluginTests extends PHPUnit_Framework_TestCase {
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
    * test block plugin tag
    */
    public function testBlockPlugin1()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat}hello world{/textformat}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    public function testBlockPlugin2()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat assign=foo}hello world{/textformat}{\$foo}",$this->smarty->tpl_vars);
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
} 

?>
