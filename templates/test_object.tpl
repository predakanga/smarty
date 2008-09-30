{assign var=x  value=33}
{assign var=x2  value=10}
{$person->object->setName('peter')->setAge($x+4)->introduce()}<BR>
{$person->object->setAge($x+$x2)->setName('paul')->introduce()}<BR>

