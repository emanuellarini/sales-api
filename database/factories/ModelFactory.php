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

$factory->define(Salesman::class, function (Faker\Generator $faker) {
    return [
        'commission' => 0
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});

$factory->state(User::class, 'salesman', function (Faker\Generator $faker) {
    $salesman = factory(Salesman::class)->create();

    return [
        'userable_id' => $salesman->id,
        'userable_type' => 'salesman',
    ];
});

$factory->define(Sale::class, function (Faker\Generator $faker) {
    $amount = $faker->numberBetween(10000, 1000000);
    $pct = 0.085;
    $commission = (int) round($amount * $pct);

    $salesman = Salesman::inRandomOrder()->first();
    $salesman->commission = ($salesman->commission * 100) + $commission;
    $salesman->save();

    return [
        'amount' => $amount,
        'commission_pct' => $pct,
        'user_id' => $salesman->user->id,
    ];
});
