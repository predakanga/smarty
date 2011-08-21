<?php

class Benchmark extends BenchmarkBase
{
    public function prepare($factor)
    {
        $this->run($factor);
    }
    
    protected function run($factor)
    {
        $this->smarty->assign('foo', 'Hello World');
        $this->smarty->assign('eval', 'foobar {$foo|escape} baz');
        return $this->smarty->fetch('Eval/StringInclude.' . $factor . '.tpl');
    }
}
