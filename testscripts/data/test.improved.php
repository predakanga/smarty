<?php

require_once(dirname(__FILE__) . '/../../distribution/libs/Smarty.class.php');
require_once(dirname(__FILE__) . '/Improved_Data.php');

error_reporting(E_ALL | E_WARNING | E_NOTICE);

$iterations = 10000;

$smarty = new Smarty();
$smarty->error_unassigned = false;
$data = new Improved_Data(null, $smarty);

/********************************************************************************
 * assign() data
 ********************************************************************************/
$__start = $start = microtime(true);
$__mem = $mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->assign('d' . $i, $i);
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d assign()\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * overwrite assign()ed data
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    // note this access is bad, because unknown variables would have to be stored in the container, to ensure consistency
    //$data->{'d' . $i}->value = $i;
    $data->assign('d' . $i, $i);
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d re-assign()\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * accessing data
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->{'d' . $i}->value;
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d accessing\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * accessing unknown data
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->{'x' . $i}->value;
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d accessing unkown\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * accessing flags
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->{'d' . $i}->nocache;
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d accessing flags\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * accessing unknown flags
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    if ($data->{'d' . $i}->_isset('unknown')) {
        $data->{'d' . $i}->unknown;
    }
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d accessing unknown flags\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * setting custom flags
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->{'d' . $i}->custom = $i;
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d setting custom flags\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);


/********************************************************************************
 * setting multiple custom flags
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

for ($i=0; $i < $iterations; $i++) {
    $data->{'d' . $i}->{'custom' . $i} = $i;
}

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for %d setting multiple custom flags\n", $_start - $start, ($_mem - $mem) / 1024 / 1024, $iterations);




/********************************************************************************
 * garbage collection hack
 * see http://bugs.php.net/bug.php?id=55033
 ********************************************************************************/
$start = microtime(true);
$mem = memory_get_usage();

// free memory wasted because of __get()
$data = clone $data;

$_mem = memory_get_usage();
$_start = microtime(true);
printf("%0.4f seconds and %0.4f MB for cloning data\n", $_start - $start, ($_mem - $mem) / 1024 / 1024);


/********************************************************************************
 * totals
 ********************************************************************************/
$_mem = memory_get_usage();
$_start = microtime(true);
printf("\n%0.4f seconds and %0.4f MB total\n", $_start - $__start, ($_mem - $__mem) / 1024 / 1024, $iterations);
