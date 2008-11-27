<?php
/**
* Smarty PHPunit tests comments in templates
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for security test
*/
class CoreTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->force_compile = true;
    } 

    public function tearDown()
    {
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /*
    * loadPlugin must exist
    */
    public function testLoadPlugin1()
    {
        $this->assertTrue(method_exists($this->smarty, 'loadPlugin'), 'loadPlugin methode must exist');
    }

    /*
    * loadPlugin test unkown plugin
    */
    public function testLoadPlugin2()
    {
        $this->assertFalse($this->smarty->loadPlugin('Smarty_Not_Known'), 'loadPlugin must return false if method is unknown');
    }
}
?>
