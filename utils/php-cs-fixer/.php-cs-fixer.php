<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/../../')
    ->exclude([
        'tests/_data',
        'tests/_output',
        'tests/_support/_generated',
        'utils/rector/tests',
        'utils/symfony-application/var',
        'vendor',
    ])
    ->notPath([
        'src/Infrastructure/Symfony/DependencyInjection/Configuration.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__.'/.php-cs-fixer.cache')
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP80Migration' => true,

        // Short array syntax
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/array_notation/array_syntax.rst
        'array_syntax' => ['syntax' => 'short'],

        // Yoda style disabled
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/control_structure/yoda_style.rst
        'yoda_style' => false,

        // Each element of an array must be indented exactly once.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/whitespace/array_indentation.rst
        'array_indentation' => true,

        // Add declare(strict_types=1);
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/strict/declare_strict_types.rst
        'declare_strict_types' => true,

        // Add void return type to functions with missing or empty return statements
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/function_notation/void_return.rst
        'void_return' => true,

        // Functions should be used with $strict param set to true.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/strict/strict_param.rst
        'strict_param' => true,

        // should not contain the name
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_var_without_name.rst
        'phpdoc_var_without_name' => true,

        // @var and @type annotations must have type and name in the correct order.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_var_annotation_correct_order.rst
        'phpdoc_var_annotation_correct_order' => true,

        // @return void and @return null annotations should be omitted from PHPDoc.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_no_empty_return.rst
        'phpdoc_no_empty_return' => true,

        // PHPDoc should contain @param for only untyped params.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_add_missing_param_annotation.rst
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],

        // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/align_multiline_comment.rst
        'align_multiline_comment' => true,

        // An empty line feed must precede any configured statement.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/whitespace/blank_line_before_statement.rst
        'blank_line_before_statement' => [
            'statements' => ['if', 'return', 'yield', 'yield_from', 'for', 'do', 'while', 'foreach', 'try', 'switch'],
        ],

        // Calling isset on multiple items should be done in one call.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/language_construct/combine_consecutive_issets.rst
        'combine_consecutive_issets' => true,

        // Calling unset on multiple items should be done in one call.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/language_construct/combine_consecutive_unsets.rst
        'combine_consecutive_unsets' => true,

        // Convert heredoc to nowdoc where possible.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/string_notation/heredoc_to_nowdoc.rst
        'heredoc_to_nowdoc' => true,

        // Remove multiple spaces after comma.
        // Ensure fully multiline
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/function_notation/method_argument_space.rst
        'method_argument_space' => ['keep_multiple_spaces_after_comma' => false, 'on_multiline' => 'ensure_fully_multiline'],

        // Method chaining MUST be properly indented. Method chaining with different levels of indentation is not supported.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/whitespace/method_chaining_indentation.rst
        'method_chaining_indentation' => true,

        // There should not be useless else cases.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/control_structure/no_useless_else.rst
        'no_useless_else' => true,

        // Properties MUST not be explicitly initialized with null except when they have a type declaration
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/class_notation/no_null_property_initialization.rst
        'no_null_property_initialization' => true,

        // Dont add "\" before native function invocation
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/function_notation/native_function_invocation.rst
        'native_function_invocation' => [],

        // Dont convert phpdoc to comment
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_to_comment.rst
        'phpdoc_to_comment' => false,

        // Spaces for binary operator.
        // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/operator/binary_operator_spaces.rst
        'binary_operator_spaces' => [
            'operators' => ['|' => 'no_space'],
        ],
    ]);
