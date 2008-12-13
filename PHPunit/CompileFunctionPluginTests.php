<?php
/**
* Smarty PHPunit tests compilation of function plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for function plugin tests
*/
class CompileFunctionPluginTests extends PHPUnit_Framework_TestCase {
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
    * test function plugin tag in template file
    */
    public function testFunctionPluginFromTemplateFile()
    {
        $tpl = $this->smarty->createTemplate('plugintest.tpl', $this->smarty->tpl_vars);
        $this->assertEquals("10", $this->smarty->fetch($tpl));
    } 
    /**
    * test function plugin function definition in script
    */
    public function testFunctionPluginRegisteredFunction()
    {
        $this->smarty->register_function('plugintest','plugintest');
        $tpl = $this->smarty->createTemplate('string:{plugintest foo=bar}', $this->smarty->tpl_vars);
        $this->assertEquals("plugin test called bar", $this->smarty->fetch($tpl));
    } 
} 
function plugintest($params, &$smarty)
{
    return "plugin test called $params[foo]";
} 

?>
