<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in('app')
    ->in('src')
    ->in('tests');

$config = Config::create()
    // use symfony level and extra fixers:
    ->setRules(array(
        '@PSR2' => true,
        'array_syntax' => array('syntax' => 'short'),
        'protected_to_private' => false,
        'mb_str_functions' =>true,
    ))
    ->setFinder($finder);

return $config;
