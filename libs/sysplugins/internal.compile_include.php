<?php

/**
* Smarty plugin
* 
* @package Smarty
* @subpackage plugins
*/
//  Compile include tag
//
//  Not yet working completely
//  The idea is just to call $smarty->fetchtoget teh work done
//
class Smarty_Internal_Compile_Include extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
         foreach ($args as $key => $value) {
            $_attr[$key] = $value;
        } 

        if (empty($_attr['file'])) {
//            $smarty->_syntax_error("missing 'file' attribute in include tag", E_USER_ERROR, __FILE__, __LINE__);
        }

        foreach ($_attr as $arg_name => $arg_value) {
            if ($arg_name == 'file') {
                $include_file = str_replace("'","",$arg_value);
                continue;
            } else if ($arg_name == 'assign') {
                $assign_var = $arg_value;
                continue;
            }
            if (is_bool($arg_value))
                $arg_value = $arg_value ? 'true' : 'false';
            $arg_list[] = "'$arg_name' => $arg_value";
        }
      
//        $output = $this->smarty->fetch($include_file);
//        $output = "<?php ";
//        if (isset($assign_var)) {
//            $output .= "ob_start();\n";
//        }

        return "<?php echo \$this->smarty->fetch('$include_file');?>";
    } 
} 

?>
