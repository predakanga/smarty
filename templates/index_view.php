PHP file test
$foo is <?=$foo?>
<br> Test modifier chaining
<?=$foo->trim()->escape('html')?>
<br>Test objects
<?=$person->setName('Paul')->setAge(39)->introduce()->trim()->truncate(10)?>
<br> Old style modifier and function calls
<?=$this->smarty->function->mailto(array('address'=>'me@example.com'))?>
<?=$this->smarty->modifier->escape($foo,'html')?>
<?=$this->smarty->modifier->trim($foo)?>
<br>Arrays
<?=$array['a']?>

DONE
