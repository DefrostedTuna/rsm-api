 <?php
 
/**
 * Config for PHP-CS-Fixer ver2
 */
$rules = [
    '@PSR2' => true,
    'array_syntax' => [
      'syntax' => 'short',
    ],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'trailing_comma_in_multiline_array' => true,
    'ordered_imports' => [
        'sortAlgorithm' => 'alpha',
    ],
];

$excludes = [
    // add exclude project directory
    'resources',
    'vendor',
    'storage',
    'node_modules',
];

$finder = PhpCsFixer\Finder::create()
  ->exclude($excludes)
  ->notName('README.md')
  ->notName('*.xml')
  ->notName('*.yml');

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules($rules)
    ->setFinder($finder);