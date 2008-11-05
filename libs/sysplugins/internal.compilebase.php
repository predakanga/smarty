<?php

/**
* Smarty Internal Plugin CompileBase
* 
* @package Smarty
* @subpackage compiler
* @author Uwe Tews
*/

class Smarty_Internal_CompileBase {
    public $smarty = null;

    function __construct()
    { 
        // $this->smarty = Smarty::instance();
        $this->smarty = Smarty::instance();
        $this->compiler = Smarty_Internal_Compiler::instance(); 
        // definition of valid attributes
        $this->required_attributes = array();
        $this->optional_attributes = array();
    } 

    function _get_attributes ($args)
    { 
        // check if all required attributes present
        foreach ($this->required_attributes as $attr) {
            if (!array_key_exists($attr, $args)) {
                $this->compiler->trigger_template_error("missing \"" . $attr . "\" attribute");
            } 
        } 
        // check for unallowed attributes
        if ($this->optional_attributes != array('_any')) {
            $tmp_array = array_merge($this->required_attributes, $this->optional_attributes);
            foreach ($args as $key => $dummy) {
                if (!in_array($key, $tmp_array)) {
                    $this->compiler->trigger_template_error("unexspected \"" . $key . "\" attribute");
                } 
            } 
        } 

        return $args;
    } 
    /**
    * push opening tag-name
    * 
    * @param string $ the opening tag's name
    */

    function _open_tag($open_tag)
    {
        array_push($this->compiler->_tag_stack, $open_tag);
    } 

    /**
    * pop closing tag-name
    * raise an error if this stack-top doesn't match with the closing tag
    * 
    * @param string $ the closing tag's name
    * @return string the opening tag's name
    */
    function _close_tag($close_tag)
    {
        $message = '';
        if (count($this->compiler->_tag_stack) > 0) {
            $_open_tag = array_pop($this->compiler->_tag_stack);
            if (in_array($_open_tag, (array)$close_tag)) {
                return $_open_tag;
            } 
            $this->compiler->trigger_template_error("unclosed {" . $_open_tag . "} tag");
            return;
        } 
        $this->compiler->trigger_template_error("unexpected closing tag");
        return;
    } 

} 

?>
