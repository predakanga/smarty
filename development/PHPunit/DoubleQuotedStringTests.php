<?php
/**
* Smarty PHPunit tests double quoted strings
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for double quoted string tests
*/
class DoubleQuotedStringTests extends PHPUnit_Framework_TestCase {
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
    * test simple double quoted string
    */
    public function testSimpleDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello World"}{$foo}', null, null, $this->smarty);
        $this->assertEquals('Hello World', $this->smarty->fetch($tpl));
    } 
    /**
    * test expression tags in double quoted strings
    */
    public function testTagsInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello {1+2} World"}{$foo}', null, null, $this->smarty);
        $this->assertEquals('Hello 3 World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars in double quoted strings
    */
    public function testVarsInDoubleQuotedString1()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello $bar World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah World', $this->smarty->fetch($tpl));
    } 
    public function testVarsInDoubleQuotedString2()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$buh=\'buh\'}{$foo="Hello $bar$buh World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blahbuh World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars with backtick in double quoted strings
    */
    public function testVarsBacktickInDoubleQuotedString1()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello `$bar`.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    public function testVarsBacktickInDoubleQuotedString2()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$buh=\'buh\'}{$foo="Hello `$bar``$buh`.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blahbuh.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test variable vars with backtick in double quoted strings
    */
    public function testVariableVarsBacktickInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$barbuh=\'blah\'}{$buh=\'buh\'}{$foo="Hello `$bar{$buh}`.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test array vars with backtick in double quoted strings
    */
    public function testArrayVarsBacktickInDoubleQuotedString1()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar[1][2]=\'blah\'}{$foo="Hello `$bar.1.2`.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    public function testArrayVarsBacktickInDoubleQuotedString2()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar[1][2]=\'blah\'}{$foo="Hello `$bar[1][2]`.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test smartytag in double quoted strings
    */
    public function testSmartytagInDoubleQuotedString1()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello {counter} World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello 1 World', $this->smarty->fetch($tpl));
    } 
    public function testSmartytagInDoubleQuotedString2()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello {counter}{counter} World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello 12 World', $this->smarty->fetch($tpl));
    } 
    /**
    * test vars in delimiter in double quoted strings
    */
    public function testVarsDelimiterInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$bar=\'blah\'}{$foo="Hello {$bar}.test World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello blah.test World', $this->smarty->fetch($tpl));
    } 
    /**
    * test escaped quotes in double quoted strings
    */
    public function testEscapedQuotesInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello \" World"}{$foo}',null, null,  $this->smarty);
        $this->assertEquals('Hello " World', $this->smarty->fetch($tpl));
    }
 
    /**
    * test single quotes in double quoted strings
    */
    public function testSingleQuotesInDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo="Hello \'World\'"}{$foo}',null, null,  $this->smarty);
       $this->assertEquals("Hello 'World'", $this->smarty->fetch($tpl));
    } 
    /**
    * test empty double quoted strings
    */
    public function testEmptyDoubleQuotedString()
    {
        $tpl = $this->smarty->createTemplate('string:{$foo=""}{$foo}',null, null,  $this->smarty);
        $this->assertEquals("", $this->smarty->fetch($tpl));
    } 
} 

?>
