<pre>SMARTY SMOKE TEST

VARIABLE TESTS:

$foo is {$foo}

$baz[1] is {$baz[1]}

$blah['b'] is {$blah['b']}

$blah[$baz[1]] is {$blah[$baz[1]]}

$foo.$baz[1] is {$foo.$baz[1]}

{"foo"}

OBJECT TESTS:

$myobj->foo is {$myobj->foo}

$myobj->test(1) is {$myobj->test(1)}
$myobj->test(1,'two') is {$myobj->test(1,'two')}
$myobj->test(count($baz)) is {$myobj->test(count($baz))}
$myobj->test($myobj->test(count($baz))) is {$myobj->test($myobj->test(count($baz)))}
$myobj->test($foo|escape) is {$myobj->test($foo|escape)}

PHP TESTS:

php $foo is <?=$foo?>

php $foo.$baz[1] is <?=$foo.$baz[1]?>

COMMENT TESTS:

{* this is a comment *}
{* another $foo comment *}
{* another <?=$foo?> comment *}
{* multi line
   comment *}
{* /* foo */ *}
A{* comment *}B
C
D{* comment *}
{* comment *}E
F
G{* multi
 line *}H
I{* multi
line *}
J

ASSIGN:

A
{assign var=zoo value="blah"}
B
C{assign var=zoo value="blah"}D
E{assign var=zoo value="blah"}
F
G
{assign var=zoo value="blah"}H

SPACING TESTS:

{$foo}

{$foo}{$foo}

A{$foo}

A{$foo}B

{$foo}B

IF TESTS:

{if $foo eq "baz"}
  IS BAZ
{elseif $foo == "lala"}
  IS LALA
{else}
  IS NONE
{/if}

{if $myint+5 EQ 9}
  IS NINE
{else}
  IS NOT NINE
{/if}

{if $myint + 5 eq 9}
  IS NINE
{else}
  IS NOT NINE
{/if}

{if count($baz)-2 eq 1}
  IS ONE
{else}
  IS NOT ONE
{/if}

{if $foo.$foo2 eq "barbar2"}
  IS BARBAR2
{else}
  IS NOT BARBAR2
{/if}

{if $not_logged}
  NOT LOGGED
{/if}

TEST INCLUDE:

{include file="header.tpl" gee="joe"}
{include file="header.tpl" gee="joe $foo bar"}
{include file=$includeme}

TEST FINISHED
</pre>
