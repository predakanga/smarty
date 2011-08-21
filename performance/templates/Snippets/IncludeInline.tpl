<ul>
{for $foo=1 to $factor}
    {include file="Snippets/Include.sub.tpl" foo=$foo inline}
{/for}
</ul>