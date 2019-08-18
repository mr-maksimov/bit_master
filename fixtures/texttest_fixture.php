<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\GildedRose;
use App\Item;

echo "OMGHAI!</br>";

$items = [
    new Item(GildedRose::DEXTERITY_VEST, 10, 20),
    new Item(GildedRose::AGED_BRIE, 2, 0),
    new Item(GildedRose::ELIXIR, 5, 7),
    new Item(GildedRose::SULFURAS, 0, 80),
    new Item(GildedRose::SULFURAS, -1, 80),
    new Item(GildedRose::BACKSTAGE, 15, 20),
    new Item(GildedRose::BACKSTAGE, 10, 49),
    new Item(GildedRose::BACKSTAGE, 5, 49),
    // this conjured item does not work properly yet
    new Item(GildedRose::CONJURED, 3, 6)
];
$app = new GildedRose($items);
$days = 3;
//Переменная $argv не была обозначена, поэтому проверим на ее существование
if ($argv && count($argv) > 1) {
    $days = (int) $argv[1];
}

for ($i = 0; $i < $days; $i++) {
    echo("-------- day $i --------</br>");
    echo("name, sellIn, quality</br>");
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->updateQuality();
}