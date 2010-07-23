<?php
/**
* Smarty PHPunit tests trigger_error method
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for trigger_error tests
*/
class TriggerErrorTests extends PHPUnit_Framework_TestCase {
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
