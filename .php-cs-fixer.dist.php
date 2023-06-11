<?php
return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_php4_constructor' => true,
        'echo_tag_syntax' => ['long_function' => 'echo'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_unreachable_default_argument_value' => true,
        'no_alternative_syntax' => false,
        'psr_autoloading' => false,
        'simplified_null_return' => true,
        'fopen_flags' => ['b_mode' => true],
        'native_function_invocation' => ['include' => ['@compiler_optimized'], 'scope' => 'all', 'strict' => true]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src/')
            ->in(__DIR__.'/tests/')
    );
