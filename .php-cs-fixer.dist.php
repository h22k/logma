<?php

$finder = (new PhpCsFixer\Finder())
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PHP83Migration' => true,
    ])
    ->setFinder($finder);
