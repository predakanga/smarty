<?php
/**
* Smarty PHPunit tests assign methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for assign tests
*/
class AssignTests extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->old_error_level = error_reporting();
        error_reporting(E_ALL);
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test simple assign
    */
    public function testSimpleAssign()
    {
            $this->smarty->assign('foo','bar');
		$this->assertEquals('bar', $this->smarty->fetch('string:{$foo}'));
    } 
    /**
    * test assign array of variables
    */
    public function testArrayAssign()
    {
            $this->smarty->assign(array('foo'=>'bar','foo2'=>'bar2'));
		$this->assertEquals('bar bar2', $this->smarty->fetch('string:{$foo} {$foo2}'));
    } 
} 
?>
