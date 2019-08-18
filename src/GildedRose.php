<?php

namespace App;

final class GildedRose {

    //Названия товара желательно хранить в таблице, вдруг произойдут переименования
    const DEXTERITY_VEST = '+5 Dexterity Vest';
    const AGED_BRIE = 'Aged Brie';
    const ELIXIR = 'Elixir';
    const SULFURAS = 'Sulfuras';
    const BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    const CONJURED = 'Conjured Mana Cake';

    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }
    /*Исходная функция, не используется delete*/
    public function updateQuality_old() {
        foreach ($this->items as $item) {
            if ($item->name != GildedRose::AGED_BRIE and $item->name != GildedRose::BACKSTAGE) {
                if ($item->quality > 0) {
                    if ($item->name != GildedRose::SULFURAS) {
                        $item->quality = $item->quality - 1;
                    } else {
                        $item->quality = 80;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == GildedRose::BACKSTAGE) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }
            
            if ($item->name != GildedRose::SULFURAS) {
                $item->sell_in = $item->sell_in - 1;
            }
            
            if ($item->sell_in < 0) {
                if ($item->name != GildedRose::AGED_BRIE) {
                    if ($item->name != GildedRose::BACKSTAGE) {
                        if ($item->quality > 0) {
                            if ($item->name != GildedRose::SULFURAS) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }

    /**
    *Увеличить качество если оно еще не больше 50
    */
    private static function qualityUp($item){
        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }
    }

    /**
    *Уменьшить качество кроме SULFURAS
    */
    private static function qualityLow($item){
        if ($item->quality > 0) {
            if ($item->name != GildedRose::SULFURAS) {
                $item->quality = $item->quality - 1;
            } else {
                $item->quality = 80;
            }
        }
    }

    /**
    *Уменьшить срок хранения кроме SULFURAS
    *После того, как срок храния прошел, качество товара ухудшается в два раза быстрее => запустим изменение качества еще раз)
    */
    private static function sell_inLow($item){
        if ($item->name != GildedRose::SULFURAS) {
            $item->sell_in = $item->sell_in - 1;
        }    
        if ($item->sell_in < 0) {
            self::qualityChange($item);
        }
    }

    /**
    *Изменение качества
    */
    private static function qualityChange($item){
        if ($item->name != GildedRose::AGED_BRIE and $item->name != GildedRose::BACKSTAGE) {
            self::qualityLow($item);
            //Доработка с магическим товаром)
            if($item->name == GildedRose::CONJURED){
                self::qualityLow($item);
            }
        } else {
            self::qualityUp($item);
            self::checkBackstage($item);
        }
    }
    /**
    *Качество увеличивается на 2, когда до истечения срока хранения 10 или менее дней и на 3,
    *если до истечения 5 или менее дней. При этом качество падает до 0 после даты проведения концерта.
    **/
    private static function checkBackstage($item){
        if ($item->name == GildedRose::BACKSTAGE) {
            if ($item->sell_in < 11) {
                self::qualityUp($item);
            }
            if ($item->sell_in < 6) {
                self::qualityUp($item);
            }
            if($item->sell_in < 0) {
                $item->quality = $item->quality - $item->quality;
            }
        }
    }
    /**
    * Обновление Качества товаров
    **/
    public function updateQuality() {
        foreach ($this->items as $item) {
            self::qualityChange($item);
            self::sell_inLow($item);
        }
    }
}