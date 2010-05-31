<?php
/**
* Smarty PHPunit tests ternary operator
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for ternary operator tests
*/
class TernaryTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test output on boolean constant
    */
    public function testTernaryOutputBoolean1()
    {
        $tpl = $this->smarty->createTemplate("string:{(true) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputBoolean2()
    {
        $tpl = $this->smarty->createTemplate("string:{(false) ? 'yes' : 'no'}");
        $this->assertEquals('no', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on variable
    */
    public function testTernaryOutputVariable1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=true}{(\$foo) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputVariable2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=false}{(\$foo) ? 'yes' : 'no'}");
        $this->assertEquals('no', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on array element
    */
    public function testTernaryOutputArray1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo[1][2]=true}{(\$foo.1.2) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputArray2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo[1][2]=true}{(\$foo[1][2]) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputArray3()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo[1][2]=false}{(\$foo.1.2) ? 'yes' : 'no'}");
        $this->assertEquals('no', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputArray4()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo[1][2]=false}{(\$foo[1][2]) ? 'yes' : 'no'}");
        $this->assertEquals('no', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on condition
    */
    public function testTernaryOutputCondition1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=true}{(\$foo === true) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputCondition2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=true}{(\$foo === false) ? 'yes' : 'no'}");
        $this->assertEquals('no', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on function
    */
    public function testTernaryOutputFunction1()
    {
        $tpl = $this->smarty->createTemplate("string:{(time()) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on template function
    */
    public function testTernaryOutputTemplateFunction1()
    {
        $tpl = $this->smarty->createTemplate("string:{({counter start=1} == 1) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    /**
    * test output on expression
    */
    public function testTernaryOutputExpression1()
    {
        $tpl = $this->smarty->createTemplate("string:{(1 + 2 === 3) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryOutputExpression2()
    {
        $tpl = $this->smarty->createTemplate("string:{((1 + 2) === 3) ? 'yes' : 'no'}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    /**
    * test assignment on boolean constant
    */
    public function testTernaryAssignBoolean1()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo=(true) ? 'yes' : 'no'}{\$foo}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    public function testTernaryAssignBoolean2()
    {
        $tpl = $this->smarty->createTemplate("string:{\$foo[1][2]=(true) ? 'yes' : 'no'}{\$foo[1][2]}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
    /**
    * test attribute on boolean constant
    */
    public function testTernaryAttributeBoolean1()
    {
        $tpl = $this->smarty->createTemplate("string:{assign var=foo value=(true) ? 'yes' : 'no'}{\$foo}");
        $this->assertEquals('yes', $this->smarty->fetch($tpl));
    } 
} 

?>