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

$factory->define(App\Models\UsersVCS::class, function (Faker $faker, $arr) {
    return [
        'user_id' => (!empty($arr['user_id'])) ? $arr['user_id'] : $faker->numberBetween(1, 100),
        'amount' => (!empty($arr['amount'])) ? $arr['amount'] : $faker->numberBetween(1, 10)
    ];
});
