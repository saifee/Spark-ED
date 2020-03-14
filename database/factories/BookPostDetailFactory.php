<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Library\BookPostDetail;
use App\Models\Configuration\Library\BookCondition;

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

$factory->define(BookPostDetail::class, function (Faker $faker) {
    
	$book_conditions = BookCondition::all()->pluck('id')->all();

    return [
        'number' => $faker->unique()->randomNumber(4),
        'location' => $faker->randomElement(['Shelf 1','Shelf 2','Shelf 3','Shelf 4','Shelf 5']),
        'is_not_available' => 0,
        'book_condition_id' => $faker->randomElement($book_conditions),
        'remarks' => $faker->realText(50),
        'options' => []
    ];
});
