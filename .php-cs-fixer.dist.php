<?php
$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        // Rulesets
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP56Migration:risky' => true,
        '@PHPUnit57Migration:risky' => true,

        // Additional rules
        'fopen_flags' => true,
        'linebreak_after_opening_tag' => true,
        'native_function_invocation' => true,
        'ordered_imports' => true,

        // --- Diffs from @PhpCsFixer / @PhpCsFixer:risky ---

        // This is just prettier / easier to read.
        'concat_space' => ['spacing' => 'one'],

        // This causes strange ordering with codegen'd classes. We might be
        // able to enable this if we update codegen to output class elements
        // in the correct order.
        'ordered_class_elements' => false,

        // Keep this disabled to avoid unnecessary diffs in PHPDoc comments of
        // codegen'd classes.
        'phpdoc_align' => false,

        // This is a "risky" rule that causes a bug in our codebase.
        // Specifically, in `StripeObject.updateAttributes` we construct new
        // `StripeObject`s for metadata. We can't use `self` there because it
        // needs to be a raw `StripeObject`.
        'self_accessor' => false,
    ])
;
