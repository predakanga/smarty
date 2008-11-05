<?php

/**
* Smarty Internal Plugin Cacher InlineCode
* 
* Process nocached code
* Version to inject nocache code directly into cache file
* if caching is disabled at render time the code is being evaluated
* @package Smarty
* @subpackage cacher
* @author Uwe Tews
*/

class Smarty_Internal_Cacher_InlineCode extends Smarty_Internal_PluginBase {
    public function processNocacheCode ($compiledCode, $compiler, $tag_nocache, $is_code)
    { 
        // If the template is not evaluated and we have a nocache section and or a nocache tag
        if ($is_code) {
            if (!$compiler->template->isEvaluated() && $compiler->template->caching &&
                    ($tag_nocache || $compiler->_compiler_status->nocache || $compiler->_compiler_status->tag_nocache)) {
                $compiler->_compiler_status->tag_nocache = false;
                $_output = str_replace("'", "\'", $compiledCode);
                $_output = '<?php  echo \'' . $_output . '\';?>'; 
                // return replacement code to compiler
                return $_output;
            } 
        }
        // return original code 
        return $compiledCode;
    } 

    public function initCacher ($compiler)
    {
        return;
    } 

    public function closeCacher ($compiler, $template_code)
    {
        return $template_code;
    } 

    public function getCachedContents ($_template)
    {
        return $_template->cache_resource_objects[$_template->caching_type]->getCachedContents($_template);
    } 

    public function writeCachedContent ($_template)
    {
        return $_template->cache_resource_objects[$_template->caching_type]->writeCachedContent($_template);
    } 
} 

?>
