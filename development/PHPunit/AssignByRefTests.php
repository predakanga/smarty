<?php
/**
* Smarty PHPunit tests assign_by_ref methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for assign_by_ref tests
*/
class AssignByRefTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
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
    * test simple assign_by_ref
    */
    public function testSimpleAssignByRef()
    {
        $bar = 'bar';
        $this->smarty->assign_by_ref('foo', $bar);
        $bar = 'newbar';
        $this->assertEquals('newbar', $this->smarty->fetch('string:{$foo}'));
    } 
} 

?>
