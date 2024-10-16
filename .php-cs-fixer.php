<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setFinder(
        Finder::create()
            ->in(__DIR__)
            ->exclude('vendor')
    )
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@DoctrineAnnotation' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => '',
            'separate' => 'none',
            'location' => 'after_declare_strict',
        ],
        'list_syntax' => [
            'syntax' => 'short'
        ],
        'concat_space' => [
            'spacing' => 'one'
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
    ])
    ->setUsingCache(false);