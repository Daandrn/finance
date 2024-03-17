<?php 

$value = 5645.54449;

$sprintf       = sprintf('%.2f', $value);
$round         = round($value, 2);
$number_format = number_format($value, 2, ',', '.');

echo "<pre>";

print_r([
    'sprintf'          => $sprintf,
    'round'            => $round,
    'number_format'    => $number_format,
]);

echo "</pre>"; 