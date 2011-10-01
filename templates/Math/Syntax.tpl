{for $foo=1 to $factor}
    {$height = 4}
    {$width = 5}
    <p>{$height + $width}</p>
    <p>{$height - $width}</p>
    <p>{$height * $width}</p>
    <p>{$height / $width}</p>

    {$x = 2}
    {$y = 10}
    {$z = 2}
    <p>{($x + $y) / $z}</p>

    {$x = 1.6}
    {$y = 9.3}
    <p>{$res = sin($x/$y)}{$res}</p> {* alternateively: {($x/$y)|sin} *}
{/for}