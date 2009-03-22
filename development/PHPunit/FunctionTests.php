<?php
/**
* Smarty PHPunit tests of function calls
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for function tests
*/
class FunctionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
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
    * test unknown function error
    */
    public function testUnknownFunction()
    {
        try {
            $this->smarty->fetch('string:{unknown()}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown function "unknown"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unknown function has not been raised.');
    } 
} 

?>
