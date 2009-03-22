<?php
/**
* Smarty PHPunit tests  of the <?xml...> tag handling
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for <?xml...> tests
*/
class XmlTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
        $this->smarty->enableSecurity();
        $this->smarty->force_compile = false;
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
    * test standard xml
    */
    public function testXml()
    {
        $tpl = $this->smarty->createTemplate('xml.tpl');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $this->smarty->fetch($tpl));
    } 
    /**
    * test standard xml
    */
    public function testXmlCaching1()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 100;
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $this->smarty->fetch('xml.tpl'));
    } 
    public function testXmlCaching2()
    {
        $this->smarty->caching = true;
        $this->smarty->caching_lifetime = 100;
        $this->assertTrue($this->smarty->is_cached('xml.tpl'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $this->smarty->fetch('xml.tpl'));
    } 
} 

?>
