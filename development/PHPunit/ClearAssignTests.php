<?php
/**
* Smarty PHPunit tests clearing assigned variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for clearing assigned variables tests
*/
class ClearAssignTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->old_error_level = error_reporting();
        $this->smarty->assign('foo','foo');
        $this->smarty->assign('bar','bar');
        $this->smarty->assign('blar','blar');
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test all variables accessable
    */
    public function testAllVariablesAccessable()
    {
		$this->assertEquals('foobarblar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 

    /**
    * test simple clear assign
    */
    public function testClearAssign()
    {
            $this->smarty->clear_assign('blar');
		$this->assertEquals('foobar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 
    /**
    * test clear assign array of variables
    */
    public function testArrayClearAssign()
    {
            $this->smarty->clear_assign(array('blar','foo'));
		$this->assertEquals('bar', $this->smarty->fetch('string:{$foo}{$bar}{$blar}'));
    } 
} 
?>
