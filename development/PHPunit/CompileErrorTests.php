<?php
/**
* Smarty PHPunit tests compiler errors
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for compiler tests
*/
class CompileErrorTests extends PHPUnit_Framework_TestCase {
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
    * test none existing template file error
    */
    public function testNoneExistingTemplateError()
    {
        try {
            $this->smarty->fetch('string:{include file=\'no.tpl\'}');
        } 
        catch (Exception $e) {
            $this->assertContains('Unable to load template', $e->getMessage());
            return;
        } 
        $this->fail('Exception for none existing template has not been raised.');
    } 
    /**
    * test unkown tag error
    */
    public function testUnknownTagError()
    {
        try {
            $this->smarty->fetch('string:{unknown}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown tag "unknown"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unknown Smarty tag has not been raised.');
    } 
    /**
    * test unclosed tag error
    */
    public function testUnclosedTagError()
    {
        try {
            $this->smarty->fetch('string:{if true}');
        } 
        catch (Exception $e) {
            $this->assertContains('unclosed {if} tag', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unclosed Smarty tags has not been raised.');
    } 
    /**
    * test syntax error
    */
    public function testSyntaxError()
    {
        try {
            $this->smarty->fetch('string:{assign var=}');
        } 
        catch (Exception $e) {
            $this->assertContains('Syntax Error in template "string"', $e->getMessage());
            $this->assertContains('Unexpected "}"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for syntax error has not been raised.');
    } 
    /**
    * test empty templates
    */
    public function testEmptyTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:');
        $this->assertEquals('', $this->smarty->fetch($tpl));
    } 

} 

?>
