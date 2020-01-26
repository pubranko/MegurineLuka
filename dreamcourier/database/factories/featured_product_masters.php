<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FeaturedProductMaster;
use Faker\Generator as Faker;

$factory->define(FeaturedProductMaster::class, function (Faker $faker) {
    return [
        'introduction_tag'=>$faker->sentence,
        'priority'=>$faker->numberBetween(0,999),
        'product_tag'=>$faker->sentence,
        'validity_period_from'=>$faker->dateTimeBetween("2020-01-01 00:00","2050-12-31 23:59"),
        'validity_period_to'=>$faker->dateTimeBetween("2020-01-02 00:00","2050-12-31 23:59"),
        'status'=>'正式',
        'temporary_updater_operator_code'=>'ope2',
        'temporary_update_approver_operator_code'=>'ope3',
    ];
});
