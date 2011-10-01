<?php

define('HTTP_BASE_PATH', 'smarty.dev/performance/');
define('ITERATIONS', 100);

define('QUANTILE', (int) ITERATIONS / 4);
define('ELEMENTS', QUANTILE * 2);

ini_set( 'allow_url_fopen', true );

require_once dirname(__FILE__) . '/data.php';

function invoke($test, $case, $factor, $method) {
    $url = 'http://' . HTTP_BASE_PATH 
        . 'invoke.php?test=' . $test 
        . '&case=' . $case 
        . '&factor=' . $factor 
        . '&method=' . $method;

    $t = file_get_contents($url);
    
    if (!$t || !strncmp($t, 'error:', 6)) {
        throw new Exception("Failed " . $url);
    }
    
    if ($method != 'evaluate') {
        return null;
    }
    
    $t = explode("\n", $t);
    return array(
        'memory' => (double) $t[0] / 1024 / 1024,
        'duration' => (double) $t[1],
    );
}

$totals = array();

foreach ($tests as $test => $factors) {
    foreach ($cases[$test] as $case => $description) {
        foreach ((array)$factors as $factor) {
            if (!$factor) {
                $factor = 1;
            }
            
            $results = array(
                'memory' => array(),
                'duration' => array(),
            );
            
            invoke($test, $case, $factor, 'prepare');
            
            for ($i=0; $i < ITERATIONS; $i++) {
                $t = invoke($test, $case, $factor, 'evaluate');
                $results['memory'][] = $t['memory'];
                $results['duration'][] = $t['duration'];
            }
            
            invoke($test, $case, $factor, 'teardown');
            
            // ignore first and last quantile to reduce impact of performance spikes in testing
            $averages = array();
            foreach (array('memory', 'duration') as $k) {
                sort($results[$k]);
                $results[$k] = array_slice($results[$k], QUANTILE, ELEMENTS);
                $averages[$k] = array_sum($results[$k]) / ELEMENTS;
            }
            
            printf("%-15s %-15s %0.4fs   %0.4fMB\n", $test . '['. $factor .']', $case, $averages['duration'], $averages['memory']);
            
            if (empty($totals[$test])) {
                $totals[$test] = array();
            }
            
            if (empty($totals[$test][$case])) {
                $totals[$test][$case] = array();
            }
            
            $totals[$test][$case][$factor] = $averages;
        }
    }
}

file_put_contents(dirname(__FILE__) .'/tmp/results.php', "<?php\n\$time = " . time() . ";\n\$totals = " . var_export($totals, true) .';');
