<?php

/*
 * List of runnable tests,
 * NameOfTest => factors
 * Factor can be (int) 1 or an array of integers.
 * If multiple factors are supplied, the test is run for each factor.
 * This allows to visualize a performance curve.
 * Systems may do very well on small factors (say handle 10 variables),
 * but do extremely miserable for higher factors (say handle 1000 variables)
 */
$tests = array(
    'Eval' => array(1, 5, 10),
    'Snippets' => array(10, 100, 1000),
);

/*
 * List of defined cases per test,
 * NameOfTest => [ NameOfCase => description, … ]
 */
$cases = array(
    'Eval' => array(
        'EvalFunction' => 'using the {eval var=$foo} function', 
        'EvalInclude' => 'using {include file="eval:$foo"}', 
        'StringInclude' => 'using {include file="string:$foo"}', 
    ),
    'Snippets' => array(
        'Include' => 'using regular {include}s',
        'IncludeInline' => 'using regular {include inline}s',
        'Function' => 'using template-{function}',
    ),
);
