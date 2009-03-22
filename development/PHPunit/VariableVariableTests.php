<?php
/**
* Smarty PHPunit tests variable variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for variable variables tests
*/
class VariableVariableTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->force_compile = true;
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
    * test variable name in variable
    */
    public function testVariableVariable1()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'bar\'}{$bar=123}{${$foo}}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
    /**
    * test part of variable name in variable
    */
    public function testVariableVariable2()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'a\'}{$bar=123}{$b{$foo}r}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
    /**
    * test several parts of variable name in variable
    */
    public function testVariableVariable3()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'a\'}{$foo2=\'r\'}{$bar=123}{$b{$foo}{$foo2}}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
    /**
    * test nesed parts of variable name in variable
    */
    public function testVariableVariable4()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'ar\'}{$foo2=\'oo\'}{$bar=123}{$b{$f{$foo2}}}', $this->smarty);
        $this->assertEquals('123', $this->smarty->fetch($tpl));
    } 
} 

?>
