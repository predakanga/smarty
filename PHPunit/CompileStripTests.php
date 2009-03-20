<?php
/**
* Smarty PHPunit tests compilation of strip tags
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once '../libs/Smarty.class.php';

/**
* class for strip tags tests
*/
class CompileStripTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = new Smarty();
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
    * test strip tag
    */
    public function testStrip()
    {
        $tpl = $this->smarty->createTemplate("string:{strip}<table>\n </table>{/strip}");
        $this->assertEquals('<table></table>', $this->smarty->fetch($tpl));
    } 
} 

?>
