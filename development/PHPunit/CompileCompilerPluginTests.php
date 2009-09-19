<?php
/**
* Smarty PHPunit tests compilation of compiler plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for compiler plugin tests
*/
class CompileCompilerPluginTests extends PHPUnit_Framework_TestCase {
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
    * test compiler plugin tag in template file
    */
    public function testCompilerPluginFromTemplateFile()
    {
        $this->smarty->register_compiler_function('compilerplugin', 'mycompilerplugin');
        $tpl = $this->smarty->createTemplate('compilerplugintest.tpl');
        $this->assertEquals("Hello World", $this->smarty->fetch($tpl));
    } 
    /**
    * test compiler plugin tag in compiled template file
    */
    public function testCompilerPluginFromCompiledTemplateFile()
    {
        $this->smarty->force_compile = false;
        $this->smarty->register_compiler_function('compilerplugin', 'mycompilerplugin');
        $tpl = $this->smarty->createTemplate('compilerplugintest.tpl');
        $this->assertEquals("Hello World", $this->smarty->fetch($tpl));
    } 
} 
function mycompilerplugin($params, $compiler)
{
    return '<?php echo \'Hello World\';?>';
} 

?>
