<?php
/**
* Smarty PHPunit tests static class access to constants, variables and methodes
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for static class access to constants, variables and methodes tests
*/
class StaticClassAccessTests extends PHPUnit_Framework_TestCase {
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
    * test static class variable
    */
    public function testStaticClassVariable()
    {
        $tpl = $this->smarty->createTemplate('string:{mystaticclass::$static_var}');
        $this->assertEquals('5', $this->smarty->fetch($tpl));
    } 
    /**
    * test static class constant
    */
    public function testStaticClassConstant()
    {
        $tpl = $this->smarty->createTemplate('string:{mystaticclass::STATIC_CONSTANT_VALUE}');
        $this->assertEquals('3', $this->smarty->fetch($tpl));
    } 
    /**
    * test static class methode
    */
    public function testStaticClassMethode()
    {
        $tpl = $this->smarty->createTemplate('string:{mystaticclass::square(5)}');
        $this->assertEquals('25', $this->smarty->fetch($tpl));
    } 
    /**
    * test static class variable methode
    */
    public function testStaticClassVariableMethode()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'square\'}{mystaticclass::$foo(5)}');
        $this->assertEquals('25', $this->smarty->fetch($tpl));
    } 
    /**
    * test static class variable methode
    */
    public function testStaticClassVariableMethode2()
    {
        $tpl = $this->smarty->createTemplate('string:{mystaticclass::$foo(5)}');
        $tpl->assign('foo','square');
        $this->assertEquals('25', $this->smarty->fetch($tpl));
    } 
} 

class mystaticclass {
    const STATIC_CONSTANT_VALUE = 3;
    public static $static_var = 5;
    
    static function square($i)
    {
        return $i*$i;
    } 
} 

?>
