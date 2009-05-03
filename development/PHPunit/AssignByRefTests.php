<?php
/**
* Smarty PHPunit tests assign_by_ref methode
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for assign_by_ref tests
*/
class AssignByRefTests extends PHPUnit_Framework_TestCase {
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
