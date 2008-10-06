<?php
//  Compiler for tags
//
//  Not yet complete
//  - name tag not yet supported
//  - nesting checks missing

class Smarty_Internal_Compile_For extends Smarty_Internal_CompileBase {
    /**
    * Compile {for ...} tag.
    * 
    * @param string $tag_args 
    * @return string 
    */
    function compile($args)
    {
        $this->required_attributes = array('ifexp', 'start', 'loop');

        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $this->_open_tag('for');

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

        // check and get attributes
        $_attr = $this->_get_attributes($args);

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

        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $_open_tag = $this->_close_tag(array('for','forelse'));
        if ($_open_tag == 'forelse')
            return "<?php } ?>";
        else
            return "<?php }} ?>";
         } 
} 
?>
