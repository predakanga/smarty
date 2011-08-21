<?php

define('BASE_DIR', dirname(__FILE__) . '/');
require_once BASE_DIR . '../distribution/libs/Smarty.class.php';

$smarty = new Smarty();
$smarty
    ->setTemplateDir( BASE_DIR . 'templates/' )
    ->setCompileDir( BASE_DIR . 'tmp/compiled/' );

require_once BASE_DIR . 'data.php';
require_once BASE_DIR . 'tmp/results.php';

$__tests = array();
$__cases = array();

foreach ($tests as $test => $t) {
    $_tests = array(
        'test' => $test,
        'data' => array(
            'memory' => array(),
            'duration' => array(),
        ),
    );
    foreach ($totals[$test] as $case => $factors) {
        $_case = array(
            'memory' => array(),
            'duration' => array(),
        );
        $data = array();
        foreach ($factors as $factor => $data) {
            if (empty($_tests['data']['memory'][$case])) {
                $_tests['data']['memory'][$case] = array();
                $_tests['data']['duration'][$case] = array();
            }
            $_tests['data']['memory'][$case][$factor] = $data['memory'];
            $_tests['data']['duration'][$case][$factor] = $data['duration'];
            
            $_case['memory'][$factor] = $data['memory'];
            $_case['duration'][$factor] = $data['duration'];
        }
        
        if (empty($__cases[$case])) {
            $__cases[$case] = array();
        }
        
        $__cases[$case][] = array(
            'test' => $test,
            'case' => $case,
            'data' => $_case,
        );
    }

    $__tests[] = $_tests;
}
/*
foreach ($__cases as $case => $data) {
    $smarty->assign('tests', $data);
    $t = $smarty->fetch('case.tpl');
    file_put_contents(BASE_DIR . 'html/' . $case . '.html', $t);
}
*/
$smarty->assign('tests', $__tests);
$t = $smarty->fetch('render/test.tpl');
file_put_contents(BASE_DIR . 'html/tests.html', $t);
