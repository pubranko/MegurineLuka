<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
#use App\User;
use App\ProductMaster;        #モデルを追加
use Faker\Generator as Faker;

$factory->define(ProductMaster::class, function (Faker $faker) {

    return [

        'product_code' => 'akagi-'.$faker->unique()->numberBetween(0,999),
        'sales_period_from' => $faker->dateTimeBetween("2020-01-01 00:00","2050-12-31 23:59"),
        'sales_period_to' => $faker->dateTimeBetween("2020-01-02 00:00","2050-12-31 23:59"),
        'product_name' => $faker->streetName,
        'product_description' => $faker->paragraph(),
        'product_price' => $faker->randomDigit(),
        'product_image' => "public/product_image/akagi-001_product_image_1578734202.jpg",
        'product_thumbnail' => "public/product_thumbnail/akagi-001_product_thumbnail_1578734202.jpg",
        'product_search_keyword' => $faker->sentence,
        'product_tag' => $faker->sentence,
        'product_stock_quantity' => $faker->randomDigit(),
        'status' => '正式',
        'selling_discontinued_classification' => '',
        'temporary_updater_operator_code' => 'ope2',
        'temporary_update_approver_operator_code' => 'ope3',
    ];
});

/**
 * 販売期間の重複チェック用
 */
$factory->state(ProductMaster::class, 'SalesPeriodDuplicationCheck', function () {
    return [
        'product_code' => 'akagi-999',
        'sales_period_from' => "2020-01-10 00:00",
        'sales_period_to' => "2020-01-10 01:00",
    ];
});