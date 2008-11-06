PHP file test
$foo is <?=$foo?>
<?=$foo->trim()->escape('html')?>

<?=$this->smarty->function->mailto(array('address'=>'me@example.com'))?>
<?=$this->smarty->modifier->escape($foo,'html')?>
<?=$this->smarty->modifier->trim($foo)?>

DONE
