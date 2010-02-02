<?php
/**
* Smarty PHPunit tests of config  variables
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for config variable tests
*/
class ConfigVarTests extends PHPUnit_Framework_TestCase {
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
    * test config varibales loading all sections
    */
    public function testConfigNumber()
    {
        $this->smarty->config_load('test.conf');
        $this->assertEquals("123.4", $this->smarty->fetch('string:{#Number#}'));
    } 
    public function testConfigText()
    {
        $this->smarty->config_load('test.conf');
        $this->assertEquals("123bvc", $this->smarty->fetch('string:{#text#}'));
    } 
    public function testConfigLine()
    {
        $this->smarty->config_load('test.conf');
        $this->assertEquals("123 This is a line", $this->smarty->fetch('string:{#line#}'));
    } 
    public function testConfigVariableAllSections()
    {
        $this->smarty->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty! Hello Section1 Hello Section2", $this->smarty->fetch('string:{#title#} {#sec1#} {#sec2#}'));
    } 
    /**
    * test config variables loading section2
    */
    public function testConfigVariableSection2()
    {
        $this->smarty->config_load('test.conf', 'section2');
        $this->assertEquals("Welcome to Smarty!  Hello Section2", $this->smarty->fetch('string:{#title#} {#sec1#} {#sec2#}'));
    } 
    /**
    * test config variables loading section special char
    */
    public function testConfigVariableSectionSpecialChar()
    {
        $this->smarty->config_load('test.conf', '/');
        $this->assertEquals("Welcome to Smarty!  special char", $this->smarty->fetch('string:{#title#} {#sec1#} {#sec#}'));
    } 
    /**
    * test config variables loading section foo/bar
    */
    public function testConfigVariableSectionFooBar()
    {
        $this->smarty->config_load('test.conf', 'foo/bar');
        $this->assertEquals("Welcome to Smarty!  section foo/bar", $this->smarty->fetch('string:{#title#} {#sec1#} {#sec#}'));
    } 
    /**
    * test config variables loading indifferent scopes
    */
    public function testConfigVariableScope()
    {
        $this->smarty->config_load('test.conf', 'section2');
        $tpl = $this->smarty->createTemplate('string:{#title#} {#sec1#} {#sec2#}');
        $tpl->config_load('test.conf', 'section1');
        $this->assertEquals("Welcome to Smarty!  Hello Section2", $this->smarty->fetch('string:{#title#} {#sec1#} {#sec2#}'));
        $this->assertEquals("Welcome to Smarty! Hello Section1 ", $this->smarty->fetch($tpl));
    } 
    /**
    * test config variables loading section2 from template
    */
    public function testConfigVariableSection2Template()
    {
        $this->assertEquals("Welcome to Smarty!  Hello Section2", $this->smarty->fetch('string:{config_load file=\'test.conf\' section=\'section2\'}{#title#} {#sec1#} {#sec2#}'));
    } 
    /**
    * test config varibales loading local
    */
    public function testConfigVariableLocal()
    {
        $this->assertEquals("Welcome to Smarty!", $this->smarty->fetch('string:{config_load file=\'test.conf\' scope=\'local\'}{#title#}')); 
        // global must be empty
        $this->assertEquals("", $this->smarty->get_config_vars('title'));
    } 
    /**
    * test config varibales loading parent
    */
    public function testConfigVariableParent()
    {
        $this->assertEquals("Welcome to Smarty!", $this->smarty->fetch('string:{config_load file=\'test.conf\' scope=\'parent\'}{#title#}')); 
        // global is parent must not be empty
        $this->assertEquals("Welcome to Smarty!", $this->smarty->get_config_vars('title'));
    } 
    /**
    * test config variables of hidden sections
    * shall display variables from hidden section
    */
    public function testConfigVariableHidden()
    {
        $this->smarty->config_read_hidden = true;
        $this->smarty->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty!Hidden Section", $this->smarty->fetch('string:{#title#}{#hiddentext#}'));
    } 
    /**
    * test config variables of disabled hidden sections
    * shall display not variables from hidden section
    */
    public function testConfigVariableHiddenDisable()
    {
        $this->smarty->config_read_hidden = false;
        $this->smarty->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty!", $this->smarty->fetch('string:{#title#}{#hiddentext#}'));
    } 
    /**
    * test config varibales loading all sections from template
    */
    public function testConfigVariableAllSectionsTemplate()
    {
        $this->smarty->config_overwrite = true;
        $this->assertEquals("Welcome to Smarty! Hello Section1 Hello Section2", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{#title#} {#sec1#} {#sec2#}'));
    } 
    /**
    * test config varibales overwrite
    */
    public function testConfigVariableOverwrite()
    {
        $this->assertEquals("Overwrite2", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{#overwrite#}'));
    } 
    public function testConfigVariableOverwrite2()
    {
        $this->assertEquals("Overwrite3", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{config_load file=\'test2.conf\'}{#overwrite#}'));
    } 
    /**
    * test config varibales overwrite false
    */
    public function testConfigVariableOverwriteFalse()
    {
        $this->smarty->config_overwrite = false;
        $this->assertEquals("Overwrite1Overwrite2Overwrite3Welcome to Smarty! Hello Section1 Hello Section2", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{config_load file=\'test2.conf\'}{foreach #overwrite# as $over}{$over}{/foreach}{#title#} {#sec1#} {#sec2#}'));
    } 
    /**
    * test config varibales booleanize on
    */
    public function testConfigVariableBooleanizeOn()
    {
        $this->smarty->config_booleanize = true;
        $this->assertEquals("passed", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{if #booleanon# === true}passed{/if}'));
    } 
    /**
    * test config varibales booleanize off
    */
    public function testConfigVariableBooleanizeOff()
    {
        $this->smarty->config_booleanize = false;
        $this->assertEquals("passed", $this->smarty->fetch('string:{config_load file=\'test.conf\'}{if #booleanon# == \'on\'}passed{/if}'));
    } 
    /**
    * test config file syntax error
    */
    public function testConfigSyntaxError()
    {
        try {
            $this->smarty->fetch('string:{config_load file=\'test_error.conf\'}');
        } 
        catch (Exception $e) {
            $this->assertContains('Syntax error in config file', $e->getMessage());
            return;
        } 
        $this->fail('Exception for syntax errors in config files has not been raised.');
    } 
    /**
    * test get_config_vars
    */
    public function testConfigGetSingleConfigVar()
    {
        $this->smarty->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty!", $this->smarty->get_config_vars('title'));
    } 
    /**
    * test get_config_vars return all variables
    */
    public function testConfigGetAllConfigVars()
    {
        $this->smarty->config_load('test.conf');
        $vars = $this->smarty->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertEquals("Welcome to Smarty!", $vars['title']);
        $this->assertEquals("Hello Section1", $vars['sec1']);
    } 
    /**
    * test clear_config for single variable
    */
    public function testConfigClearSingleConfigVar()
    {
        $this->smarty->config_load('test.conf');
        $this->smarty->clear_config('title');
        $this->assertEquals("", $this->smarty->get_config_vars('title'));
    } 
    /**
    * test clear_config for all variables
    */
    public function testConfigClearConfigAll()
    {
        $this->smarty->config_load('test.conf');
        $this->smarty->clear_config();
        $vars = $this->smarty->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertTrue(empty($vars));
    } 
    /**
    * test config vars on data object
    */
    public function testConfigTextData()
    {
        $data = $this->smarty->createData();
        $data->config_load('test.conf');
        $this->assertEquals("123bvc", $this->smarty->fetch('string:{#text#}', $data));
    } 
    /**
    * test get_config_vars on data object
    */
    public function testConfigGetSingleConfigVarData()
    {
        $data = $this->smarty->createData();
        $data->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty!", $data->get_config_vars('title'));
    } 
    /**
    * test get_config_vars return all variables on data object
    */
    public function testConfigGetAllConfigVarsData()
    {
        $data = $this->smarty->createData();
        $data->config_load('test.conf');
        $vars = $data->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertEquals("Welcome to Smarty!", $vars['title']);
        $this->assertEquals("Hello Section1", $vars['sec1']);
    } 
    /**
    * test clear_config for single variable on data object
    */
    public function testConfigClearSingleConfigVarData()
    {
        $data = $this->smarty->createData();
        $data->config_load('test.conf');
        $data->clear_config('title');
        $this->assertEquals("", $data->get_config_vars('title'));
        $this->assertEquals("Hello Section1", $data->get_config_vars('sec1'));
    } 
    /**
    * test clear_config for all variables on data object
    */
    public function testConfigClearConfigAllData()
    {
        $data = $this->smarty->createData();
        $data->config_load('test.conf');
        $data->clear_config();
        $vars = $data->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertTrue(empty($vars));
    } 
    /**
    * test config vars on template object
    */
    public function testConfigTextTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:{#text#}');
        $tpl->config_load('test.conf');
        $this->assertEquals("123bvc", $this->smarty->fetch($tpl));
    } 
    /**
    * test get_config_vars on template object
    */
    public function testConfigGetSingleConfigVarTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:{#text#}');
        $tpl->config_load('test.conf');
        $this->assertEquals("Welcome to Smarty!", $tpl->get_config_vars('title'));
    } 
    /**
    * test get_config_vars return all variables on template object
    */
    public function testConfigGetAllConfigVarsTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:{#text#}');
        $tpl->config_load('test.conf');
        $vars = $tpl->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertEquals("Welcome to Smarty!", $vars['title']);
        $this->assertEquals("Hello Section1", $vars['sec1']);
    } 
    /**
    * test clear_config for single variable on template object
    */
    public function testConfigClearSingleConfigVarTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:{#text#}');
        $tpl->config_load('test.conf');
        $tpl->clear_config('title');
        $this->assertEquals("", $tpl->get_config_vars('title'));
        $this->assertEquals("Hello Section1", $tpl->get_config_vars('sec1'));
    } 
    /**
    * test clear_config for all variables on template object
    */
    public function testConfigClearConfigAllTemplate()
    {
        $tpl = $this->smarty->createTemplate('string:{#text#}');
        $tpl->config_load('test.conf');
        $tpl->clear_config();
        $vars = $tpl->get_config_vars();
        $this->assertTrue(is_array($vars));
        $this->assertTrue(empty($vars));
    } 

    /**
    * test config varibales loading from absolute file path
    */
    public function testConfigAbsolutePath()
    {
        $file = realpath($this->smarty->config_dir . 'test.conf');
        $this->smarty->config_load($file);
        $this->assertEquals("123.4", $this->smarty->fetch('string:{#Number#}'));
    } 
} 

?>
