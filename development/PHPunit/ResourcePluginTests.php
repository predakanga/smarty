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
     * test resource plugin rendering of a registered object
     */
    public function testResourcePluginRegisteredInstance()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->smarty->loadPlugin('Smarty_Resource_Db2');
        $this->smarty->registerResource( 'db2a', new Smarty_Resource_Db2() );
        $this->assertEquals('hello world', $this->smarty->fetch('db2a:test'));
    }
    /**
     * test resource plugin rendering of a recompiling resource
     */
    public function testResourcePluginRecompiled()
    {
        return;
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        try {
            $this->assertEquals('hello world', $this->smarty->fetch('db3:test'));
        } catch (Exception $e) {
            $this->assertContains('not return a destination', $e->getMessage());
            return;
        }
        $this->fail('Exception for empty filepath has not been thrown.');
    }
    /**
     * test resource plugin non-existent compiled cache of a recompiling resource
     */
    public function testResourcePluginRecompiledCompiledFilepath()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('db2:test.tpl');
        $expected = realpath('./templates_c/'.sha1('db2:test.tpl').'.db2.test.tpl.php');
        $this->assertFalse(!!$expected);
        $this->assertFalse($tpl->compiled->filepath);
    }
    /**
     * test resource plugin rendering of a custom resource
     */
    public function testResourcePluginMysql()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->assertEquals('hello world', $this->smarty->fetch('mysqltest:test.tpl'));
    }
    /**
     * test resource plugin timestamp of a custom resource
     */
    public function testResourcePluginMysqlTimestamp()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('mysqltest:test.tpl');
        $this->assertEquals(strtotime("2010-12-25 22:00:00"), $tpl->source->timestamp);
    }
    /**
     * test resource plugin timestamp of a custom resource with only fetch() implemented
     */
    public function testResourcePluginMysqlTimestampWithoutFetchTimestamp()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('mysqlstest:test.tpl');
        $this->assertEquals(strtotime("2010-12-25 22:00:00"), $tpl->source->timestamp);
    }
    /**
     * test resource plugin compiledFilepath of a custom resource
     */
    public function testResourcePluginMysqlCompiledFilepath()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('mysqltest:test.tpl');
        $expected = realpath('./templates_c/'.sha1('mysqltest:test.tpl').'.mysqltest.test.tpl.php');
        $this->assertTrue(!!$expected);
        $this->assertEquals($expected, realpath($tpl->compiled->filepath));
    }
    public function testResourcePluginMysqlCompiledFilepathCache()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
        $this->smarty->force_compile = true;
        $this->smarty->fetch('mysqltest:test.tpl');
        $tpl = $this->smarty->createTemplate('mysqltest:test.tpl');
        $expected = realpath('./templates_c/'.sha1('mysqltest:test.tpl').'.mysqltest.test.tpl.cache.php');
        $this->assertTrue(!!$expected);
        $this->assertEquals($expected, realpath($tpl->compiled->filepath));
        $this->smarty->caching = false;
    }
    /**
     * test resource plugin timesatmp
     */
    public function testResourcePluginTimestamp()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $tpl = $this->smarty->createTemplate('db:test');
        $this->assertTrue(is_integer($tpl->source->timestamp));
        $this->assertEquals(10, strlen($tpl->source->timestamp));
    } 

} 

?>