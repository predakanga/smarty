<?php
//  Compiler foreach tags
//
//  Not yet complete
//  - name tag not yet supported
//  - nesting checks missing

class Smarty_Internal_Compile_For extends Smarty_Internal_CompileBase {
    /**
    * Compile {foreach ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
            $this->_open_tag('for');
         foreach ($args as $key => $value) {
            $_attr[$key] = $value;
        } 
        $output = "<?php $_attr[start]; if ($_attr[ifexp]) { for ($_attr[start];$_attr[ifexp];$_attr[loop]) {";
        $output .= "?>";


        return $output;
    } 
} 
class Smarty_Internal_Compile_Forelse extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {forelse} tag
        * 
       * @return string 
        */ 
        $this->_close_tag('for');
        $this->_open_tag('forelse');
        return "<?php }} else { ?>";
    } 
} 
class Smarty_Internal_Compile_End_For extends Smarty_Internal_CompileBase {
    public function compile($args)
    {
        /**
        * Compile {/for} tag
        * 
       * @return string 
        */ 
        $_open_tag = $this->_close_tag(array('for','forelse'));
        if ($_open_tag == 'forelse')
            return "<?php } ?>";
        else
            return "<?php }} ?>";
         } 
} 
?>
