<?php

function render_strngs(array $words, $count)
{
    $max_words = count($words) - 1;
    $strings = [];

    while($count--) {
        $key = mt_rand(1, $max_words);
        $word = $words[$key];
        $words[$key] = $words[0];
        $words[0] = $word;

        $strings[] = implode(' ', $words);
    }

    return $strings;
}

function get_uniques(array $strings)
{
    return array_keys(array_flip($strings));
}

$words = ['red', 'green', 'yellow', 'blue', 'orange'];

$t = microtime(true);
$strings = render_strngs($words, 10000000);
echo "T = ".(microtime(true) - $t)."\n";

$t = microtime(true);
$uniques = get_uniques($strings);
echo "T = ".(microtime(true) - $t)."\n";
print_r($uniques);