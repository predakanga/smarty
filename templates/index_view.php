This is an example of a PHP template (not compiled.)

Test function:
<?php
  $mailto = new Smarty_Function_Mailto;
  echo $mailto->execute(array('address'=>'me@example.com'));
?>

<?php 
  echo time();
?>

Test variable:
foo is <?=$foo?>.<?php echo $foo;?>

Test modifier:


Finished.

