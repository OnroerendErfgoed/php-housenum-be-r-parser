<?php

require_once( __DIR__ . '/../vendor/autoload.php' );

use OE\HousenumBErParser\Facade;

$facade = new Facade();

echo "Splitting 15-21\n";

$split = $facade->split('15-21');

foreach($split as $hnr) {
    echo $hnr . "\n";
}
echo "\n";

echo "Splitting 17/1-5\n";

$split = $facade->split('17/1-5');

foreach($split as $hnr) {
    echo $hnr . "\n";
}
echo "\n";

echo "Splitting 15, 17/1-3, 20 bus A-C\n";

$split = $facade->split('15, 17/1-3, 20 bus A-C');

foreach($split as $hnr) {
    echo $hnr . "\n";
}
echo "\n";

echo "Merging 17/1, 15, 20 bus C, 20 bus B, 17/3, 17/2, 20 bus A\n";

$merge = $facade->merge('17/1, 15, 20 bus C, 20 bus B, 17/3, 17/2, 20 bus A');

echo $facade->numbersToString($merge) . "\n";
