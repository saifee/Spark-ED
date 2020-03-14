<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Library\BookPost;

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

$factory->define(BookPost::class, function (Faker $faker) {
    
    return [
        'date_of_addition' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
        'quantity' => $faker->numberBetween(1,5),
        'remarks' => $faker->realText(50),
        'options' => []
    ];
});
