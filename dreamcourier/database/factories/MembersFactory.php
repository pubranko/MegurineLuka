<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
#use App\User;
use App\Member;                 #Memberモデルを追加
use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
/*
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
*/

$factory->define(Member::class, function (Faker $faker) {

    #$faker = \Faker\Factory::create('ja_JP');

    $phone_number1 = explode("-",$faker->phoneNumber)[0];
    $phone_number2 = explode("-",$faker->phoneNumber)[1];
    $phone_number3 = explode("-",$faker->phoneNumber)[2];

    return [
        'member_code' => $faker->randomDigit(1, 90000),
        'email' => $faker->unique()->safeEmail,
        'password'  => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'last_name' => $faker->lastName,
        'first_name' => $faker->firstName,
        'last_name_kana' => $faker->lastKanaName,
        'first_name_kana' => $faker->firstKanaName,
        'birthday' => $faker->dateTimeBetween('-30 years', 'now',),
        #date_of_birth("", 0, 115),
        #date_between("-30y", "today"),
        'sex' => $faker->randomElement(['男性', '女性']),
        'postal_code1' => $faker->postcode1,
        'postal_code2' => $faker->postcode2,
        'address1' => $faker->prefecture,
        'address2' => $faker->city,
        'address3' => $faker->ward,
        'address4' => $faker->areaNumber,
        'address5' => $faker->buildingNumber,   #ビルと部屋番号が一体、、、まあ、任意項目だからとりあえずこのまま。
        #'address6' => $faker->building_number,
        'phone_number1' => $phone_number1,
        'phone_number2' => $phone_number2,
        'phone_number3' => $phone_number3,
        'enrollment_datetime' => $faker->dateTimeBetween('-30 years', 'now',),
        #'unsubscribe_reason' => $faker->,
        'status' => "正式",
        #'purchase_stop_division' => $faker->,
        #'temporary_update_operator_code' => $faker->,
        #'temporary_update_approval_operator_code' => $faker->,
        #'create_at' => $faker->dateTimeBetween('-30 years', 'now',),
        #'update_at' => $faker->dateTimeBetween('-30 years', 'now',),
    ];
});

/*
'member_code', 'email', 'password',
'last_name','first_name','last_name_kana','first_name_kana','birthday','sex',
'postal_code1','postal_code2',
'address1','address2','address3','address4','address5','address6',
'phone_number1','phone_number2','phone_number3',
'enrollment_datetime','unsubscribe_reason','status','purchase_stop_division',
'temporary_update_operator_code','temporary_update_approval_operator_code',
'create_at','update_at',
*/
