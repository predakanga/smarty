<?php
/**
* Smarty PHPunit tests  of the <?xml...> tag handling
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for <?xml...> tests
*/
class XmlTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
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
        $this->smarty->cache_lifetime = 1000;
        $content = $this->smarty->fetch('xml.tpl');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $content);
    } 
    public function testXmlCaching2()
    {
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 1000;
 //       $this->assertTrue($this->smarty->is_cached('xml.tpl'));
        $content = $this->smarty->fetch('xml.tpl');
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $content);
    } 
} 

?>
