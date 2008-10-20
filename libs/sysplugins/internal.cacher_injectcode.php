<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
/**
* Cache Handler
* Process nocached code
* Version to inject nocache code directly into cache file
* if caching is disabled at render time the code is being evaluated
*/

class Smarty_Internal_Cacher_InjectCode extends Smarty_Internal_PluginBase {

    public function processNocacheCode ($compiledCode)
    {
        $this->compiler = Smarty_Internal_Compiler::instance(); 
        // If the template is not evaluated and we have a nocache section and or a nocache tag
        if (!$this->compiler->template->isEvaluated() &&
                ($this->compiler->_compiler_status->tag_nocache || $this->compiler->_compiler_status->nocache)) {
            $this->compiler->_compiler_status->tag_nocache = false;
            $_output = str_replace("'", "\'", $compiledCode);
            $_output = "<?php \$_tmp = '$_output'; if (\$this->caching) echo \$_tmp; else eval(\$_tmp);?>"; 

            // return replacement code to compiler
            return $_output;
        } else {
            return $compiledCode;
        } 
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
