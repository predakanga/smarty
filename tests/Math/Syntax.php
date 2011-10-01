<?php

class Benchmark extends BenchmarkBase
{
    public function prepare($factor)
    {
        $this->run($factor);
    }
    
    protected function run($factor)
    {
        $this->smarty->assign('factor', $factor);
        return $this->smarty->fetch('Math/Syntax.tpl');
    }
}
