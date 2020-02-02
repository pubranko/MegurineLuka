<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductStockList;
use Faker\Generator as Faker;

$factory->define(ProductStockList::class, function (Faker $faker) {
    return [
        'product_code' => 'akagi-'.$faker->unique()->numberBetween(0,999),
        'product_stock_quantity' => $faker->randomDigit(),
    ];
});