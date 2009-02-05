<?php
/**
* Smarty PHPunit tests double quoted strings
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for double quoted string tests
*/
class DoubleQuotedStringTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
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
    * test simple double quoted string
    */
    public function testSimpleDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello World', $this->smarty->fetch($tpl));
    } 
    /**
    * test expression tags in double quoted strings
    */
    public function testTagsInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello {1+2} World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello 3 World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars in double quoted strings
    */
    public function testVarsInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello $bar World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello blah World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars with backtick in double quoted strings
    */
    public function testVarsBacktickInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello `$bar`.test World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars in delimiter in double quoted strings
    */
    public function testVarsDelimiterInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello {$bar}.test World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test escaped quotes in double quoted strings
    */
    public function testEscapedQuotesInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello \"\" World"}{$foo}', $this->smarty);
        $this->assertEquals('Hello "" World', $this->smarty->fetch($tpl));
    } 
    /**
    * test single quotes in double quoted strings
    */
    public function testSingleQuotesInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello \'World\'"}{$foo}', $this->smarty);
        $this->assertEquals("Hello 'World'", $this->smarty->fetch($tpl));
    } 
    /**
    * test empty double quoted strings
    */
    public function testEmptyDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=""}{$foo}', $this->smarty);
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
} 

?>
