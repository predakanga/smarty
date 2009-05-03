<?php
/**
* Smarty PHPunit tests of function calls
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for function tests
*/
class FunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = Smarty::instance();
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test unknown function error
    */
    public function testUnknownFunction()
    {
        try {
            $this->smarty->fetch('string:{unknown()}');
        } 
        catch (Exception $e) {
            $this->assertContains('PHP function "unknown" not allowed by security setting', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unknown function has not been raised.');
    } 
} 

?>
