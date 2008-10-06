<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Internal_Compiler extends Smarty_Internal_Base {

  public function compile($tpl_filepath,$compiled_path) {
  
    /* here is where the compiling takes place. Smarty
       tags in the templates are replaces with PHP code,
       then written to compiled files. For now, we just
       copy the template to the compiled file. */    
  
    $content = file_get_contents($tpl_filepath);
    
    // replace {$foo} with
    $content = preg_replace('!{(\$\w+.*)}!U','<?php echo ${1}; ?>'."\n",$content);
    
    return file_put_contents($compiled_path,$content);
  }

}

?>