<?php

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = false;
$smarty->cache_lifetime = 10;

 class Person
{
    private $m_szName;
    private $m_iAge;
    
    public function setName($szName)
    {
        $this->m_szName = $szName;
        return $this; // We now return $this (the Person)
    }
    
    public function setAge($iAge)
    {
        $this->m_iAge = $iAge;
        return $this; // Again, return our Person
    }
    
    public function introduce()
    {
          return  'Hello my name is '.$this->m_szName.' and I am '.$this->m_iAge.' years old.';
    }
}  

$person->object = new Person;

$smarty->assign('person',$person);

// example of executing a compiled template
$smarty->display('test_object.tpl');

?>
