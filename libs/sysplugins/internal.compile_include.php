<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
// Compile include tag

// Not yet working completely
// The idea is just to call $smarty->fetchtoget teh work done

class Smarty_Internal_Compile_Include extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        $this->required_attributes = array('file'); 
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $include_file = str_replace("'", "", $_attr['file']);

        return "<?php echo \$this->smarty->fetch('$include_file');?>";
    } 
} 

?>
