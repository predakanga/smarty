<?php

abstract class BenchmarkBase extends Benchmarker
{
    protected $smarty = null;
    
    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty
            ->setTemplateDir(BASE_DIR . 'templates/')
            ->setCompileDir(BASE_DIR . 'tmp/compiled/')
            ->setCacheDir(BASE_DIR . 'tmp/cached/');

        $this->smarty->caching = 0;
    }
}