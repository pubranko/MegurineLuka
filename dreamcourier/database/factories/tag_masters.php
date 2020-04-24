<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TagMaster;
use Faker\Generator as Faker;

$factory->define(TagMaster::class, function (Faker $faker) {
    return [
        'introduction_tag' => 'ギャンブル！',
        'priority' => '20',
        'tag_level' => '1',
        'children_tag' => 'アカギ　ワシズ',
        'product_tag' => 'ギャンブル',
        'validity_period_from' => $faker->dateTimeBetween('2020-01-01 00:00','2020-01-10 23:59'),
        'validity_period_to' => $faker->dateTimeBetween('2100-12-01 00:00','2100-12-31 23:59'),
        'status' => '正式',
        'temporary_updater_operator_code' => 'ope2',
        'temporary_update_approver_operator_code' => 'ope3',
        #'create_at' => '',
        #'update_at' => '',
    ];
});
