<?php

$finder = \PhpCsFixer\Finder::create()
->exclude('vendor')
->exclude('node_modules')
->exclude('bower_components')
->in(__DIR__);

return \PhpCsFixer\Config::create()
->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
])

->setFinder($finder);
