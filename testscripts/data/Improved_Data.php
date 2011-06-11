<?php

/*
    split variables and config into separate objects:
    Smarty_Internal_Template::$data = new Improved_Data();
    Smarty_Internal_Template::$config = new Improved_Data();
*/

class Improved_Data
{
    public static $unknown_variable = null;
    public $_Improved_Data_smarty = null;
    public $_Improved_Data_parent = null;
    
	public function __construct($smarty=null, $parent=null)
	{
        $this->_Improved_Data_smarty = $smarty;
        $this->_Improved_Data_parent = $parent;
	}

	public function __set($name, $value)
    {
        if ($value instanceof Improved_Variable) {
            $this->$name = $value;
        } else {
            $this->assign($name, $value);
        }
    }
    
    public function __get($name)
    {
        // the requested variable was not found
        
        // use $this->_Improved_Data_parent and $this->_Improved_Data_smarty to
        // check if variable is loadable from parent element
	    // check if default_variable_handler is set
	    // check if a notice / exception should be thrown
        
        return self::$unknown_variable;
    }
    
    public function __isset($name)
    {
        // note that isset() returns false on null values, if that's not desired the result of get_object_vars() must be analyzed
        return isset($this->$name);
    }
    
    public function __unset($name)
    {
        if (isset($this->$name)) {
            unset($this->$name);
        }
    }

	/**
     * assigns a Smarty variable
     *
     * @param array|string $name the template variable name or list ["name" => "value"]
     * @param mixed $value the value to assign
     * @param boolean $nocache if true any output of this variable will be not cached
     */
	public function assign($name, $value=null, $nocache=false)
	{
        // TODO: if $nocache is parent, throw away whatever var is assigned
        // get the reference of the parent's variable and assign directly to that
	                            
		if (is_array($name)) {
            foreach ($name as $_key => $_val) {
                if ($_key != '') {
                    if (isset($this->$_key)) {
                        $this->$_key->value = $_val;
                        $this->$_key->nocache = $nocache;
                    } else {
                        $this->$_key = new Improved_Variable($_val, $nocache);
                    }
                }
            }
        } else {
            if ($name != '') {
                if (isset($this->$name)) {
                    $this->$name->value = $value;
                    $this->$name->nocache = $nocache;
                } else {
                    $this->$name = new Improved_Variable($value, $nocache);
                }
            }
        }
	}
	
	/**
     * assigns values to template variables by reference
     *
     * @param string $name the template variable name
     * @param mixed &$value the referenced value to assign
     * @param boolean $nocache if true any output of this variable will be not cached
     */
	public function assignByRef($name, &$value, $nocache=false)
	{
		if ($name != '') {
		    $this->$name = new Improved_Variable(null, $nocache);
            $this->$name->value = &$value;
        }
	}
	
	public function append($name, $value=null, $merge=false, $nocache=false)
	{
	    // TODO: add append()
	}
	
	public function appendByRef($name, &$value, $merge=false)
	{
	    // TODO: add appendByRef()	    
	}
	
	public function clear($name) // formerly clearAssign() clearConfig()
	{
	    if (isset($this->$name)) {
	        unset($this->$name);
	    }
	}
	
	public function clearAll() // formerly clearAllAssign() clearAllConfig()
	{
        foreach (get_object_vars($this) as $name => $value) {
            if ($name == '_Improved_Data_smarty' || $name == '_Improved_Data_parent') {
                continue;
            }
            
            unset($this->$name);
        }
	}
}

Improved_Data::$unknown_variable = new Improved_Variable();

class Improved_Variable {
    // template variable
    public $value;
    public $nocache;
    public $scope;
    /**
     * create Smarty variable object
     *
     * @param mixed $value the value to assign
     * @param boolean $nocache if true any output of this variable will be not cached
     * @param boolean $scope the scope the variable will have  (local,parent or root)
     */
    public function __construct($value=null, $nocache=false, $scope=Smarty::SCOPE_LOCAL)
    {
        $this->value = $value;
        $this->nocache = $nocache;
        $this->scope = $scope;
    }
    
    // __isset($name) causes memleak
    public function _isset($name)
    {
        return isset($this->$name);
    }
    
    // __unset($name) causes memleak
    public function _unset($name)
    {
        unset($this->$name);
    }

    public function __toString()
    {
        return $this->value;
    }
}