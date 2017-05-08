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
        'email' => $faker->unique()->safeEmail
    ];
});

$factory->define(Sale::class, function (Faker\Generator $faker) {
    $amount = $faker->numberBetween(1000, 1000000);
    $pct = 0.085;

    $user = User::where('userable_type', 'salesman')->inRandomOrder()->first();
    $user->userable->commission = $user->userable->commission + ($amount * $pct ) ;
    $user->userable->save();

    return [
        'amount' => $amount,
        'commission_pct' => $pct * 100,
        'user_id' => $user->id,
    ];
});
