<?php
/**
* Smarty PHPunit tests of delimiter
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for delimiter tests
*/
class DelimiterTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->error_reporting = E_ALL;
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
        $this->old_error_level = error_reporting();
    } 

    public function tearDown()
    {
        error_reporting($this->old_error_level);
        unset($this->smarty);
        Smarty::$template_objects = null;
    } 

    /**
    * test <{ }> delimiter
    */
    public function testDelimiter1()
    {
        $this->smarty->left_delimiter = '<{';
        $this->smarty->right_delimiter = '}>';
        $tpl = $this->smarty->createTemplate('string:<{* comment *}><{if true}><{"hello world"}><{/if}>');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test <-{ }-> delimiter
    */
    public function testDelimiter2()
    {
        $this->smarty->left_delimiter = '<-{';
        $this->smarty->right_delimiter = '}->';
        $tpl = $this->smarty->createTemplate('string:<-{* comment *}-><-{if true}-><-{"hello world"}-><-{/if}->');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test <--{ }--> delimiter
    */
    public function testDelimiter3()
    {
        $this->smarty->left_delimiter = '<--{';
        $this->smarty->right_delimiter = '}-->';
        $tpl = $this->smarty->createTemplate('string:<--{* comment *}--><--{if true}--><--{"hello world"}--><--{/if}-->');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test {{ }} delimiter
    */
    public function testDelimiter4()
    {
        $this->smarty->left_delimiter = '{{';
        $this->smarty->right_delimiter = '}}';
        $tpl = $this->smarty->createTemplate('string:{{* comment *}}{{if true}}{{"hello world"}}{{/if}}');
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
} 

?>
