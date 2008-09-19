This is an example of a PHP template (not compiled.)

Test function:
<?php
  $mailto = new Smarty_Function_Mailto;
  echo $mailto->execute(array('address'=>'me@example.com'));
?>

Test variable:
foo is <?=$foo?>.

Test modifier:


Finished.

