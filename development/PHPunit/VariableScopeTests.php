<?php
/**
* Smarty PHPunit tests variable scope
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for variable scope test
*/
class VariableScopeTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->assign('foo', 'bar');
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test root variable
    */
    public function testVariableScope1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo}", null, null, $this->smarty);
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 

    /**
    * test root variable with data object chain
    */
    public function testVariableScope2()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data2 = new Smarty_Data($data1);
        $tpl = $this->smarty->createTemplate("string:{\$foo}", null, null, $data2);
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 

    /**
    * test overwrite variable with data object chain
    */
    public function testVariableScope3()
    {
        $data1 = new Smarty_Data($this->smarty);
        $data1->assign('foo','newvalue');
        $data2 = new Smarty_Data($data1);
        $tpl = $this->smarty->createTemplate("string:{\$foo}", null, null, $data2);
        // must see the new value
        $this->assertEquals("newvalue", $this->smarty->fetch($tpl));
        $tpl->parent = $this->smarty;
        // rerender
        $tpl->renderTemplate();
        // must see the old value at root
        $this->assertEquals("bar", $this->smarty->fetch($tpl));
    } 

    /**
    * test local variable not seen global
    */
    public function testVariableScope4()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo2='localvar'}{\$foo2}", null, null, $this->smarty);
        // must see local value
        $this->assertEquals("localvar", $this->smarty->fetch($tpl));
        // must see $foo2
        $tpl2 = $this->smarty->createTemplate("string:{\$foo2}", null, null, $this->smarty);
        $this->assertEquals("", $this->smarty->fetch($tpl2));
    } 

    /**
    * test overwriting by global variable
    */
    public function testVariableScope5()
    {
        // create variable $foo2
        $this->smarty->assign('foo2','oldvalue');
        $tpl = $this->smarty->createTemplate("string:{assign var=foo2 value='newvalue' scope=parent}{\$foo2}", null, null, $this->smarty);
        // must see the new value
        $this->assertEquals("newvalue", $this->smarty->fetch($tpl));
        $tpl2 = $this->smarty->createTemplate("string:{\$foo2}", null, null, $this->smarty);
        // must see the new value at root
        $this->assertEquals("newvalue", $this->smarty->fetch($tpl2));
    } 

    /**
    * test creation of global variable in outerscope
    */
    public function testVariableScope6()
    {
        // create global variable $foo2 in template
        $tpl = $this->smarty->createTemplate("string:{assign var=foo2 value='newvalue' scope=parent}{\$foo2}", null, null, $this->smarty);
        // must see the new value
        $this->assertEquals("newvalue", $this->smarty->fetch($tpl));
        $tpl2 = $this->smarty->createTemplate("string:{\$foo2}", null, null, $this->smarty);
        // must see the new value at root
        $this->assertEquals("newvalue", $this->smarty->fetch($tpl2));
    } 
} 

?>
