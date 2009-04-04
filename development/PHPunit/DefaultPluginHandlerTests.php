<?php
/**
* Smarty PHPunit tests default plugin handler
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for default plugin handler tests
*/
class DefaultPluginHandlerTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        // $this->smarty->enableSecurity();
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
    * test error on unknow template plugin
    */
    public function testUnknownTemplatePlugin()
    {
        try {
            $this->smarty->fetch('string:{myplugin}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown tag "myplugin"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none existing template function has not been raised.');
    } 
    /**
    * test error on registration on none plugin handler function.
    */
    public function testRegisterNoneExistentPluginHandlerFunction()
    {
        try {
            $this->smarty->registerDefaultPluginHandler('foo');
        } 
        catch (Exception $e) {
            $this->assertContains('Default plugin handler "foo" not callable', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none callable function has not been raised.');
    } 
    /**
    * test replacement by default template handler
    */
    public function testDefaultPluginHandler()
    {
        $this->smarty->registerDefaultPluginHandler('my_plugin_handler');
        $this->assertEquals('hello world', $this->smarty->fetch('string:{myplugin}'));
    } 
    /**
    * test default plugin handler returning false
    */
    public function testDefaultPluginHandlerReturningFalse()
    {
        $this->smarty->registerDefaultPluginHandler('my_plugin_false');
        try {
            $this->smarty->fetch('string:{myplugin}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown tag "myplugin"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none existing template has not been raised.');
    } 
} 

function my_plugin_handler ($name, $type, &$tpl)
{
    $tpl->smarty->register_function('myplugin', 'myplugin');
    return true;
} 
function myplugin($params, &$smarty)
{
    return "hello world";
} 

function my_plugin_false ($name, $type, &$tpl)
{
    return false;
} 

?>
