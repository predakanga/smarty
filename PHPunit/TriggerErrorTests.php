<?php
/**
* Smarty PHPunit tests trigger_error method
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for trigger_error tests
*/
class TriggerErrorTests extends PHPUnit_Framework_TestCase {
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
    * test error message
    */
    public function testTriggerError()
    {
        try {
            $this->smarty->trigger_error('Test error');
        } 
        catch (Exception $e) {
            $this->assertContains('Test error', $e->getMessage());
            return;
        } 
        $this->fail('Exception for custom error message of trigger_error missing');
    } 

} 

?>
