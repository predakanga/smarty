<?php
/**
 * Smarty PHPunit tests resource plugins
 * 
 * @package PHPunit
 * @author Uwe Tews 
 */

/**
 * class for resource plugins tests
 */
class ResourcePluginTests extends PHPUnit_Framework_TestCase {
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
     * test resource plugin rendering
     */
    public function testResourcePlugin()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->assertEquals('hello world', $this->smarty->fetch('db:test'));
    } 
    /**
     * test resource plugin rendering
     */
    public function testResourcePluginObject()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->assertEquals('hello world', $this->smarty->fetch('db2:test'));
    }
    /**
     * test resource plugin rendering
     */
    public function testResourcePluginRegisteredInstance()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->smarty->loadPlugin('Smarty_Resource_Db2');
        $this->smarty->registerResource( 'db2a', new Smarty_Resource_Db2() );
        $this->assertEquals('hello world', $this->smarty->fetch('db2a:test'));
    }
    public function testResourcePluginRecompiled()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->smarty->loadPlugin('Smarty_Resource_Db3');
        $this->smarty->registerResource( 'db2a', new Smarty_Resource_Db2() );
        try {
            $this->assertEquals('hello world', $this->smarty->fetch('db3:test'));
        } catch (Exception $e) {
            $this->assertContains('not return a destination', $e->getMessage());
            return;
        }
        $this->fail('Exception for empty filepath has not been thrown.');
    }
    public function testResourcePluginCompiledFilepath()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('db4:test.tpl');
        $expected = './templates_c/'.sha1($this->smarty->template_dir[0].'test.tpl').'.db4.test.tpl.php';
        $this->assertEquals(realpath($expected), realpath($tpl->getCompiledFilepath()));
    }
    /**
     * test resource plugin timesatmp
     */
    public function testResourcePluginTimestamp()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('db:test');
        $this->assertTrue(is_integer($tpl->getTemplateTimestamp()));
        $this->assertEquals(10, strlen($tpl->getTemplateTimestamp()));
    } 
} 

?>