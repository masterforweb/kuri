<?php


if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

return (new PhpCsFixer\Config())
    ->setRules([
        'full_opening_tag'                              => true,
        'no_closing_tag'                                => true,
        'linebreak_after_opening_tag'                   => true, 
        'array_syntax'                                  => ['syntax' => 'long'],
        'array_indentation'                             => true,
        'include'                                       => true,
        'no_multiline_whitespace_around_double_arrow'   => true,
        'encoding'                                      => true,
        'braces'                                        => true,
        'constant_case'                                 => [ 'case' => 'lower',],
        'lowercase_keywords'                            => true,
        'lowercase_static_reference'                    => true,
        'function_typehint_space'                       => true,
        'function_declaration'                          => true,
        'single_line_throw'                             => true,
        'no_spaces_after_function_name'                 => true,
        'declare_parentheses'                           => true,
        'single_line_comment_style'                     => true,
        'elseif'                                        => true,
        'no_alias_language_construct_call'              => true,
        'multiline_whitespace_before_semicolons'        => ['strategy' => 'no_multi_line'],
        'lowercase_cast'                                => true,
        'cast_spaces'                                   => true,
        'magic_constant_casing'                         => true,
        'magic_method_casing'                           => true,
        'no_empty_comment'                              => true,
        'no_empty_phpdoc'                               => true,
        'no_empty_statement'                            => true,
        'no_extra_blank_lines'                          => true,
        'native_function_casing'                        => true,
        'native_function_type_declaration_casing'       => true,
        'no_useless_else'                               => true,
        'no_useless_return'                             => true,
        'no_trailing_whitespace'                        => true,
        'no_short_bool_cast'                            => true,
        'short_scalar_cast'                             => true,
        'no_unset_cast'                                 => true,
        'blank_line_after_namespace'                    => true,
        'blank_line_before_statement'                   => true,
        'dir_constant'                                  => true,
        'single_blank_line_at_eof'                      => true,
        'single_blank_line_before_namespace'            => true,
        'multiline_comment_opening_closing'             => true,
        'no_trailing_whitespace_in_comment'             => true,
        'explicit_string_variable'                      => true,
        'simple_to_complex_string_variable'             => true,
        'single_quote'                                  => true,
        'compact_nullable_typehint'                     => true,
        'phpdoc_order'                                  => true,
        'phpdoc_separation'                             => true,
        'phpdoc_single_line_var_spacing'                => true,
        'phpdoc_trim'                                   => true,
        

    ])
    ->setRiskyAllowed(true)
    ->setFinder((new PhpCsFixer\Finder())
                    ->in(__DIR__.'/src')
                    ->in(__DIR__.'/tests')
                    ->name('*.php')
                )
;