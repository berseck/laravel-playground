<?php

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

$factory->define(App\Models\UsersTransactions::class, function (Faker $faker, $arr) {
    return [
        'from' => (!empty($arr['from'])) ? $arr['from'] : $faker->numberBetween(1, 50),
        'to' => (!empty($arr['to'])) ? $arr['to'] : $faker->numberBetween(51, 100),
        'amount' => (!empty($arr['amount'])) ? $arr['amount'] : $faker->numberBetween(1, 10),
        'type' => (!empty($arr['type'])) ? $arr['type'] : $faker->numberBetween(1, 2),
        'flag' => (!empty($arr['flag'])) ? $arr['flag'] : $faker->numberBetween(1, 3),
        'group_id' => (!empty($arr['group_id'])) ? $arr['group_id'] : $faker->uuid,
        'unseen' => (!empty($arr['unseen'])) ? $arr['unseen'] : 1,
    ];
});
