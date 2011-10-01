{for $foo=1 to $factor}
    {$height = 4}
    {$width = 5}
    <p>{math equation="x + y" x=$height y=$width}</p>
    <p>{math equation="x - y" x=$height y=$width}</p>
    <p>{math equation="x * y" x=$height y=$width}</p>
    <p>{math equation="x / y" x=$height y=$width}</p>

    {$x = 2}
    {$y = 10}
    {$z = 2}
    <p>{math equation="(( x + y ) / z )" x=$x y=$y z=$z}</p>

    {$x = 1.6}
    {$y = 9.3}
    <p>{math equation="sin( x / y )" x=$x y=$y}</p>
{/for}