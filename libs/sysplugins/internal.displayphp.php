<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/

class Smarty_Internal_DisplayPHP extends Smarty_Internal_Base {
    public function display($tpl, $tpl_vars)
    {
        extract($tpl_vars);
        $_filepath = $this->get_template_filepath($tpl);
        include($_filepath);
    } 
    
    public function get_template_filepath($tpl)
    {
      foreach((array)$this->smarty->template_dir as $_template_dir)
      {
        $_filepath = "$_template_dir/$tpl";
        if(file_exists($_filepath))
          return $_filepath;
        throw new SmartyException("Unable to load template '{$tpl}'");
        return false;
      }
    }
} 

?>
