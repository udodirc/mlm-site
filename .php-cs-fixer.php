<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app')     // Можно добавить папки src, routes и др.
    ->in(__DIR__ . '/routes')
    ->name('*.php')
    ->notName('*.blade.php')    // Исключаем Blade-шаблоны

;

return (new Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'blank_line_before_statement' => ['statements' => ['return']],
        // Добавляй сюда любые правила
    ])
    ->setFinder($finder);
