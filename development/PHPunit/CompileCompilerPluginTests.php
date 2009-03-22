<?php
/**
* Smarty PHPunit tests compilation of compiler plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/

require_once SMARTY_DIR . 'Smarty.class.php';

/**
* class for compiler plugin tests
*/
class CompileCompilerPluginTests extends PHPUnit_Framework_TestCase {
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
    * test compiler plugin tag in template file
    */
    public function testCompilerPluginFromTemplateFile()
    {
        $this->smarty->register_compiler_function('compilerplugin', 'mycompilerplugin');
        $tpl = $this->smarty->createTemplate('compilerplugintest.tpl', $this->smarty->tpl_vars);
        $this->assertEquals("Hello World", $this->smarty->fetch($tpl));
    } 
    /**
    * test compiler plugin tag in compiled template file
    */
    public function testCompilerPluginFromCompiledTemplateFile()
    {
        $this->smarty->force_compile = false;
        $this->smarty->register_compiler_function('compilerplugin', 'mycompilerplugin');
        $tpl = $this->smarty->createTemplate('compilerplugintest.tpl', $this->smarty->tpl_vars);
        $this->assertEquals("Hello World", $this->smarty->fetch($tpl));
    } 
} 
function mycompilerplugin($params, $compiler)
{
    return '<?php echo \'Hello World\';?>';
} 

?>
