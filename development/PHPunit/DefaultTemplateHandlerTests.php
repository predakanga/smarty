<?php
/**
* Smarty PHPunit tests deault template handler
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for block plugin tests
*/
class DefaultTemplateHandlerTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
//        $this->smarty->enableSecurity();
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
    * test error on unknow template
    */
    public function testUnknownTemplate()
    {
        try {
            $this->smarty->fetch('foo.tpl');
        } 
        catch (Exception $e) {
            $this->assertContains('Unable to load template', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none existing template has not been raised.');
    } 
    /**
    * test error on registration on none existent handler function.
    */
    public function testRegisterNoneExistentHandlerFunction()
    {
        try {
            $this->smarty->registerDefaultTemplateHandler('foo');
        } 
        catch (Exception $e) {
            $this->assertContains('Default template handler "foo" not callable', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none callable function has not been raised.');
    } 
    /**
    * test replacement by default template handler
    */
    public function testDefaultTemplateHandlerReplacement()
    {
        $this->smarty->registerDefaultTemplateHandler('my_template_handler');
        $this->assertEquals("Recsource foo.tpl of type file not found", $this->smarty->fetch('foo.tpl'));
    } 
    /**
    * test default template handler returning fals
    */
    public function testDefaultTemplateHandlerReturningFalse()
    {
        $this->smarty->registerDefaultTemplateHandler('my_false');
        try {
            $this->smarty->fetch('foo.tpl');
        } 
        catch (Exception $e) {
            $this->assertContains('Unable to load template', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none existing template has not been raised.');
    } 
    
}

function my_template_handler ($resource_type, $resource_name, &$template_source, &$template_timestamp, &$tpl)
{
    $output = "Recsource $resource_name of type $resource_type not found";
    $template_source = $output;
    $template_timestamp = time();
    return true;
} 
function my_false ($resource_type, $resource_name, &$template_source, &$tpl)
{
    return false;
} 

?>
