<?php
/**
* Smarty PHPunit tests single quoted strings
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for single quoted string tests
*/
class SingleQuotedStringTests extends PHPUnit_Framework_TestCase {
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
    * test simple single quoted string
    */
    public function testSimpleSingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'Hello World\'}{$foo}', $this->smarty);
        $this->assertEquals('Hello World', $this->smarty->fetch($tpl));
    } 
    /**
    * test that tags not interpreted in single quoted strings
    */
    public function testTagsInSingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'Hello {1+2} World\'}{$foo}', $this->smarty);
        $this->assertEquals('Hello {1+2} World', $this->smarty->fetch($tpl));
    } 
    /**
    * test that vars not interpreted in single quoted strings
    */
    public function testVarsInSingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'Hello $bar World\'}{$foo}', $this->smarty);
        $this->assertEquals('Hello $bar World', $this->smarty->fetch($tpl));
    } 
    /**
    * test double quotes in single quoted strings
    */
    public function testDoubleQuotesInSingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'Hello "World"\'}{$foo}', $this->smarty);
        $this->assertEquals('Hello "World"', $this->smarty->fetch($tpl));
    } 
    /**
    * test escaped single quotes in single quoted strings
    */
    public function testEscapedSingleQuotesInSingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'Hello \\\'World\'}{$foo}', $this->smarty);
        $this->assertEquals("Hello 'World", $this->smarty->fetch($tpl));
    } 
    /**
    * test empty single quoted strings
    */
    public function testEmptySingleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=\'\'}{$foo}', $this->smarty);
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
} 

?>
