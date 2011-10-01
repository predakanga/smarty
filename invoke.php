<?php
error_reporting(E_ALL);
define('INIT_MEMORY', memory_get_usage());
define('BASE_DIR', dirname(__FILE__) . '/');

require_once BASE_DIR . 'data.php';
$methods = array("prepare" => true, "evaluate" => true, "teardown" => true);

if (empty($_GET['test']) || empty($tests[$_GET['test']])) {
    echo 'error: unknown test';
    return;
}

if (empty($_GET['case']) || empty($cases[$_GET['test']][$_GET['case']])) {
    echo 'error: unknown test case';
    return;
}

if (empty($_GET['method']) || empty($methods[$_GET['method']])) {
    echo 'error: unknown method';
    return;
}

require_once BASE_DIR . '../trunk/distribution/libs/Smarty.class.php';
require_once BASE_DIR . 'Benchmarker.php';
require_once BASE_DIR . 'BenchmarkBase.php';

// load test
require_once BASE_DIR . 'tests/' . $_GET['test'] . '/' . $_GET['case'] . '.php';
$benchmark = new Benchmark();

// run test
$res = $benchmark->{$_GET['method']}(empty($_GET['factor']) ? 1 : $_GET['factor']);

if (!empty($_GET['output'])) {
    // output generated template
    echo $res;
} else {
    // output results
    echo $benchmark->getMemory() . "\n" . $benchmark->getDuration();
}
