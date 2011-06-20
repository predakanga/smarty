<?php
/**
* Smarty PHPunit tests chained loading of dependend pluglind
*
* @package PHPunit
* @author Rodney Rehm
*/

/**
* class for modifier tests
*/
class PluginChainedLoadTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    }

    public static function isRunnable()
    {
        return true;
    }

    public function testPluginChainedLoad()
    {
        $this->assertContains('from chain3', $this->smarty->fetch('test_plugin_chained_load.tpl'));
    }


}

?>