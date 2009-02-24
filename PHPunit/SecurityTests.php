<?php
/**
* Smarty PHPunit tests for security
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for security test
*/
class SecurityTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->plugins_dir = array('..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = true;
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
    * test that security is loaded
    */
    public function testSecurityLoaded()
    {
        $this->assertTrue(is_object($this->smarty->security_policy));
    } 


    /**
    * test trusted PHP function
    */
    public function testTrustedPHPFunction()
    {
        $this->assertEquals("5", $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{count($foo)}'));
    } 

    /**
    * test not trusted PHP function
    */
    public function testNotTrustedPHPFunction()
    {
        $this->smarty->security_policy->php_functions = array('null');
        try {
            ob_start();
            $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{count($foo)}');
        } 
        catch (Exception $e) {
            $this->assertContains('PHP function "count" not allowed by security setting', ob_get_clean());
            return;
        } 
        $this->fail('Exception for not trusted modifier has not been raised.');
    } 

    /**
    * test not trusted PHP function at disabled security
    */
    public function testDisabledTrustedPHPFunction()
    {
        $this->smarty->security_policy->php_functions = array('null');
        $this->smarty->security = false;
        $this->assertEquals("5", $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{count($foo)}'));
    } 

    /**
    * test trusted modifer
    */
    public function testTrustedModifier()
    {
        $this->assertEquals("5", $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{$foo|count}'));
    } 

    /**
    * test not trusted modifier
    */
    public function testNotTrustedModifer()
    {
        $this->smarty->security_policy->modifiers = array('null');
        try {
            ob_start();
            $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{$foo|count}');
        } 
        catch (Exception $e) {
            $this->assertContains('modifier "count" not allowed by security setting', ob_get_clean());
            return;
        } 
        $this->fail('Exception for not trusted function plugin has not been raised.');
    } 

    /**
    * test not trusted modifer at disabled security
    */
    public function testDisabledTrustedMofifer()
    {
        $this->smarty->security_policy->modifiers = array('null');
        $this->smarty->security = false;
        $this->assertEquals("5", $this->smarty->fetch('string:{assign var=foo value=[1,2,3,4,5]}{$foo|count}'));
    } 

    /**
    * test SMARTY_PHP_QUOTE
    */
    public function testSmartyPhpQuote()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_QUOTE;
        $this->assertEquals("&lt;?php echo &quot;hello world&quot;; ?&gt;", $this->smarty->fetch('string:<?php echo "hello world"; ?>'));
    } 
    public function testSmartyPhpQuote2()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_QUOTE;
        $this->assertEquals("&lt;?php echo &quot;hello world&quot;; ?&gt;", $this->smarty->fetch('string:{php}echo "hello world";{/php}'));
    } 
    /**
    * test SMARTY_PHP_REMOVE
    */
    public function testSmartyPhpRemove()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_REMOVE;
        $this->assertEquals("", $this->smarty->fetch('string:<?php echo "hello world"; ?>'));
    } 
    public function testSmartyPhpRemove2()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_REMOVE;
        $this->assertEquals("", $this->smarty->fetch('string:{php} echo "hello world"; {/php}'));
    } 
    /**
    * test SMARTY_PHP_ALLOW
    */
    public function testSmartyPhpAllow()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_ALLOW;
        $this->assertEquals("hello world", $this->smarty->fetch('string:<?php echo "hello world"; ?>'));
    } 
    public function testSmartyPhpAllow2()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_ALLOW;
        $this->assertEquals("hello world", $this->smarty->fetch('string:{php} echo "hello world"; {/php}'));
    } 
    /**
    * test PHP handling at disabled security
    */
    public function testDisabledSmartyPhpRemove()
    {
        $this->smarty->security_policy->php_handling = SMARTY_PHP_REMOVE;
        $this->smarty->security = false;
        $this->assertEquals("hello world", $this->smarty->fetch('string:<?php echo "hello world"; ?>'));
    } 

    /**
    * test standard directory
    */
    public function testStandardDirectory()
    {
        $this->assertEquals("hello world", $this->smarty->fetch('string:{include file="helloworld.tpl"}'));
    } 

    /**
    * test trusted directory
    */
    public function testTrustedDirectory()
    {
        $this->smarty->security_policy->secure_dir = array('.' . DIRECTORY_SEPARATOR . 'templates_2' . DIRECTORY_SEPARATOR);
        $this->assertEquals("hello world", $this->smarty->fetch('string:{include file="./templates_2/hello.tpl"}'));
    } 

    /**
    * test not trusted directory
    */
    public function testNotTrustedDirectory()
    {
        try {
            $this->smarty->fetch('string:{include file="./templates_2/hello.tpl"}');
        } 
        catch (Exception $e) {
            $this->assertContains('\PHPunit\templates_2\hello.tpl" not allowed by security setting', $e->getMessage());
            return;
        } 
        $this->fail('Exception for not trusted directory has not been raised.');
    } 

    /**
    * test disabled security for not trusted dir
    */
    public function testDisabledTrustedDirectory()
    {
        $this->smarty->security = false;
        $this->assertEquals("hello world", $this->smarty->fetch('string:{include file="./templates_2/hello.tpl"}'));
    } 
} 

?>
