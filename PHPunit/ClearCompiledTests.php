<?php
/**
* Smarty PHPunit tests for deleting compiled templates
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for delete compiled template tests
*/
class ClearCompiledTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test clear_compiled_tpl method
    */
    public function testClearCompiledAll()
    {
		$this->smarty->clear_compiled_tpl();
		file_put_contents($this->smarty->compile_dir.'dummy.php', 'test');
		$this->assertEquals(1, $this->smarty->clear_compiled_tpl());
    } 
} 

?>
