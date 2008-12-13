<?php
/**
* Smarty PHPunit tests register_function / unregister_function methods
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for register_function / unregister_function methods tests
*/
class RegisterFunctionTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
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
    * test register_function method
    */
    public function testRegisterFunction()
    {
		$this->smarty->register_function('testfunction','myfunction');
		$this->assertEquals('myfunction', $this->smarty->plugins['function']['testfunction'][0]);
    } 
    /**
    * test unregister_function method
    */
    public function testUnegisterFunction()
    {
		$this->smarty->register_function('testfunction','myfunction');
		$this->smarty->unregister_function('testfunction');
		$this->assertFalse(isset($this->smarty->plugins['function']['testfunction']));
    } 
} 
function myfunction($params, &$smarty)
{
    return "hello world";
} 

?>
