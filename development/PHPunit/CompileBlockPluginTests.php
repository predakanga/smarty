<?php
/**
* Smarty PHPunit tests compilation of block plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for block plugin tests
*/
class CompileBlockPluginTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test block plugin tag
    */
    public function testBlockPluginNoAssign()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat}hello world{/textformat}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag with assign attribute
    */
    public function testBlockPluginAssign()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat assign=foo}hello world{/textformat}{\$foo}", $this->smarty);
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag in template file
    */
    public function testBlockPluginFromTemplateFile()
    {
        $tpl = $this->smarty->createTemplate('blockplugintest.tpl', $this->smarty);
        $this->assertEquals("abc", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag in compiled template file
    */
    public function testBlockPluginFromCompiledTemplateFile()
    {
        $this->smarty->force_compile = false;
        $tpl = $this->smarty->createTemplate('blockplugintest.tpl', $this->smarty);
        $this->assertEquals("abc", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin function definition in script
    */
    public function testBlockPluginRegisteredFunction()
    {
        $this->smarty->register_block('blockplugintest', 'myblockplugintest');
        $tpl = $this->smarty->createTemplate('string:{blockplugintest}hello world{/blockplugintest}');
        $this->assertEquals('block test', $this->smarty->fetch($tpl));
    } 
} 
function myblockplugintest($params, $content, &$smarty_tpl, &$repeat)
{
    if (!$repeat) {
        $output = str_replace('hello world', 'block test', $content);
        return $output;
    } 
} 

?>
