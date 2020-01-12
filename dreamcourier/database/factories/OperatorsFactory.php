<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Operator;                 #Operatorモデルを追加
use Faker\Generator as Faker;

$factory->define(Operator::class, function (Faker $faker) {

    return [
        'name' => 'オペレーター１', #$faker->name,
        'operator_code' => 'ope1', #$faker->unique()->randomDigit(1, 90000),
        'email' => 'ope1@ex.com', #$faker->unique()->safeEmail,
        'password'  => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});

/**
 * E-mailの重複チェックのバリデート用
 */
/*$factory->state(Member::class, 'EmailUnique', function () {
    return [
        'email' => 'unique@ex.com',
    ];
});
*/