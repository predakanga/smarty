<?php

namespace foo\bar;

// note: this won't work with define()
const FOO = 'CONSTANT';

function foo()
{
    return 'FUNCTION';
}

class Baz {
    const FOO = 'CONSTANT';
    public static $FOO = 'STATIC';
    public static function foo()
    {
        return 'FUNCTION';
    }
}
