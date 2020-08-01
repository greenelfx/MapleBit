<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Profile;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'account_id' => $faker->unique()->numberBetween(1, User::count()),
        'name' => \Atrox\Haikunator::haikunate(),
        'age' => rand(10, 100),
        'country' => 'United States',
        'motto' => $faker->sentence(6),
        'about' => $faker->text(200),
    ];
});
