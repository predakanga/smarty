<?php
/**
* Smarty PHPunit tests default plugin handler
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for default plugin handler tests
*/
class DefaultPluginHandlerTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
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
