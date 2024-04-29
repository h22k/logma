<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->files()
    ->name('*.php')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return (new Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PHP83Migration' => true,
        'global_namespace_import' => true,
    ])
    ->setFinder($finder)
;
