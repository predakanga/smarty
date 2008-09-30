Dieses ist ein test 
{$foo}  
{assign var=c value=-1232}
{$c} <br>
{assign var=d value=2}
{-$d}
{assign var=b value=$c|substr:$d}
{foreach item=g from=$aa}
      {$g}  <br>
{/foreach}
{$b}
{$aa.$d}

<br>cached time {$t1}
{nocache}
<br>nocached time {$t2}
{/nocache}
<br>cached text


