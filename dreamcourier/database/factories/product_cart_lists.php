<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductCartList;
use Faker\Generator as Faker;

$factory->define(ProductCartList::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween(0,999),
        'member_code' => $faker->numberBetween(0,999),
        'payment_status' => $faker->randomElement(['未決済', '決済', 'キャンセル']),
    ];
});
