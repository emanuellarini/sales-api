<?php

use App\Models\Salesman;
use App\Models\Sale;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    $salesman = Salesman::create();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'userable_id' => $salesman->id,
        'userable_type' => Salesman::class,
    ];
});

$factory->define(Sale::class, function (Faker\Generator $faker) {
    $value = $faker->numberBetween(1000, 1000000);
    $pct = 0.085;

    $salesman = Salesman::inRandomOrder()->first();
    $salesman->commission = $salesman->commission + ($value * $pct );
    $salesman->save();

    return [
        'value' => $value,
        'comission_pct' => $pct * 100,
        'salesman_id' => $salesman->id,
    ];
});
