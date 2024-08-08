<?php

namespace App\Helpers;

class CalculateCarClassPrices
{
    public static function getPrice(int $price, float $distance): int
    {
        return ceil($distance / 2 * ($price / $distance)) + $price;
    }
}
