<?php
/**
* Smarty PHPunit tests deault template handler
* 
* @package PHPunit
* @author Rodney Rehm
*/


/**
* class for block plugin tests
*/
class ReflectionTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 


    public function testModifierSmarty()
    {
        $expected = 'hello world yeah!yeah!';
        $this->smarty->assign('having_fun', "yeah!");
        $this->smarty->registerPlugin('modifier', 'modifier_with_smarty', array('ReflectionTests', 'modifier_with_smarty'));
        $this->assertEquals($expected, $this->smarty->fetch('eval:{"hello world"|modifier_with_smarty:2}'));
    } 

    public static function modifier_with_smarty(Smarty $smarty, $string, $repeat=1)
    {
        return $string .' '. str_repeat($smarty->getTemplateVars('having_fun'), $repeat);
    }
    
    public function testModifierTemplate()
    {
        $expected = 'hello world yup!yup!';
        $this->smarty->registerPlugin('modifier', 'modifier_with_template', array('ReflectionTests', 'modifier_with_template'));
        $tpl = $this->smarty->createTemplate('eval:{"hello world"|modifier_with_template:2}');
        $tpl->assign('having_fun', "yup!");
        $this->assertEquals($expected, $tpl->fetch());
    } 

    public static function modifier_with_template(Smarty_Internal_Template $template, $string, $repeat=1)
    {
        return $string .' '. str_repeat($template->getTemplateVars('having_fun'), $repeat);
    }
    
    public function testModifierFail()
    {
        $this->smarty->registerPlugin('modifier', 'modifier_with_smarty_fail', array('ReflectionTests', 'modifier_with_smarty_fail'));
        try {
            $this->smarty->fetch('eval:{"hello world"|modifier_with_smarty_fail}');
        } catch(SmartyCompilerException $e) {
            $this->assertContains('repeat', $e->getMessage());
            return;
        }
        
        $this->fail("Mandatory Argument not passed");
    } 

    public static function modifier_with_smarty_fail(Smarty $smarty, $string, $repeat)
    {
        // this should never be executed!
    }
    

    public function testFunctionSmarty()
    {
        $expected = 'hello world yeah!yeah!';
        $this->smarty->assign('having_fun', "yeah!");
        $this->smarty->registerPlugin('function', 'function_with_smarty', array('ReflectionTests', 'function_with_smarty'));
        $this->assertEquals($expected, $this->smarty->fetch('eval:{function_with_smarty content="hello world" repeat=2}'));
    } 

    public static function function_with_smarty(Smarty $smarty, $content, $repeat=1)
    {
        return $content .' '. str_repeat($smarty->getTemplateVars('having_fun'), $repeat);
    }
    
    public function testFunctionTemplate()
    {
        $expected = 'hello world yup!yup!';
        $this->smarty->registerPlugin('function', 'function_with_template', array('ReflectionTests', 'function_with_template'));
        $tpl = $this->smarty->createTemplate('eval:{function_with_template content="hello world" repeat=2}');
        $tpl->assign('having_fun', "yup!");
        $this->assertEquals($expected, $tpl->fetch());
    } 

    public static function function_with_template(Smarty_Internal_Template $template, $content, $repeat=1)
    {
        return $content .' '. str_repeat($template->getTemplateVars('having_fun'), $repeat);
    }
    
    public function testFunctionFail()
    {
        $this->smarty->registerPlugin('function', 'function_with_smarty_fail', array('ReflectionTests', 'function_with_smarty_fail'));
        try {
            $this->smarty->fetch('eval:{function_with_smarty_fail content="hello world"}');
        } catch(SmartyCompilerException $e) {
            $this->assertContains('repeat', $e->getMessage());
            return;
        }
        
        $this->fail("Mandatory Argument not passed");
    } 

    public static function function_with_smarty_fail(Smarty $smarty, $content, $repeat)
    {
        // this should never be executed!
    }
    
    
    public function testBlockSmarty()
    {
        $expected = 'con-tent hello world 2 yeah!';
        $this->smarty->assign('having_fun', "yeah!");
        $this->smarty->registerPlugin('block', 'block_with_smarty', array('ReflectionTests', 'block_with_smarty'));
        $this->assertEquals($expected, $this->smarty->fetch('eval:{block_with_smarty first="hello world" second=2}con-tent{/block_with_smarty}'));
    } 

    public static function block_with_smarty(Smarty $smarty, $content, &$repeat, $first, $second)
    {
        return $content 
            .' '. $first
            .' '. $second
            .' '. $smarty->getTemplateVars('having_fun');
    }
    
    public function testBlockTemplate()
    {
        $expected = 'con-tent hello world 3 yup!';
        $this->smarty->registerPlugin('block', 'block_with_template', array('ReflectionTests', 'block_with_template'));
        $tpl = $this->smarty->createTemplate('eval:{block_with_template first="hello world" second=3}con-tent{/block_with_template}');
        $tpl->assign('having_fun', "yup!");
        $this->assertEquals($expected, $tpl->fetch());
    } 

    public static function block_with_template(Smarty_Internal_Template $template, $content, &$repeat, $first, $second)
    {
        return $content 
            .' '. $first
            .' '. $second
            .' '. $template->getTemplateVars('having_fun');
    }
    
    public function testBlockFail()
    {
        $this->smarty->registerPlugin('block', 'block_with_smarty_fail', array('ReflectionTests', 'block_with_smarty_fail'));
        try {
            $this->smarty->fetch('eval:{block_with_smarty_fail first="hello world"}con-tent{/block_with_smarty_fail}');
        } catch(SmartyCompilerException $e) {
            $this->assertContains('second', $e->getMessage());
            return;
        }
        
        $this->fail("Mandatory Argument not passed");
    } 

    public static function block_with_smarty_fail(Smarty $smarty, $content, &$repeat, $first, $second)
    {
        // this should never be executed!
    }
    
    public function testBlockFailContent()
    {
        $this->smarty->registerPlugin('block', 'block_with_smarty_fail_content', array('ReflectionTests', 'block_with_smarty_fail_content'));
        try {
            $this->smarty->fetch('eval:{block_with_smarty_fail_content first="hello world" content="nono"}con-tent{/block_with_smarty_fail_content}');
        } catch(SmartyCompilerException $e) {
            $this->assertContains('content', $e->getMessage());
            return;
        }
        
        $this->fail("Argument content passed");
    } 

    public static function block_with_smarty_fail_content(Smarty $smarty, $content, &$repeat, $first)
    {
        // this should never be executed!
    }
    
    public function testBlockFailRepeat()
    {
        $this->smarty->registerPlugin('block', 'block_with_smarty_fail_repeat', array('ReflectionTests', 'block_with_smarty_fail_repeat'));
        try {
            $this->smarty->fetch('eval:{block_with_smarty_fail_repeat first="hello world" repeat="nono"}con-tent{/block_with_smarty_fail_repeat}');
        } catch(SmartyCompilerException $e) {
            $this->assertContains('repeat', $e->getMessage());
            return;
        }
        
        $this->fail("Argument repeat passed");
    } 

    public static function block_with_smarty_fail_repeat(Smarty $smarty, $content, &$repeat, $first)
    {
        // this should never be executed!
    }
    
    
    public function testBlockNocache()
    {
        //$this->smarty->fetch('eval:{smarty_block_annotated_nocache param1="hello world" param2=2}con-tent{/smarty_block_annotated_nocache}');
        // TODO: uwe.tews add test for auto-loaded annotated nocache blocks
    }
    
    public function testFunctionNocache()
    {
        //$this->smarty->fetch('eval:{smarty_function_annotated_nocache param1="hello world" param2=2}');
        // TODO: uwe.tews add test for auto-loaded annotated nocache blocks
    }
}

/**
 * @smarty_nocache
 * @smarty_cache_attr param1, param2
 */
function smarty_block_annotated_nocache($params, $content, $template, &$repeat) {
    
}

/**
 * @smarty_nocache
 * @smarty_cache_attr param1, param2
 */
function smarty_function_annotated_nocache($params, $template) {
    
}

?>