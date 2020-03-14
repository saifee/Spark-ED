<?php

use Illuminate\Support\Str;
use App\Models\Library\Book;
use Faker\Generator as Faker;
use App\Models\Configuration\Library\BookTopic;
use App\Models\Configuration\Library\BookAuthor;
use App\Models\Configuration\Library\BookLanguage;
use App\Models\Configuration\Library\BookPublisher;

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

$factory->define(Book::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\Book($faker));

	$book_authors    = BookAuthor::all()->pluck('id')->all();
	$book_languages  = BookLanguage::all()->pluck('id')->all();
	$book_publishers = BookPublisher::all()->pluck('id')->all();
	$book_topics     = BookTopic::all()->pluck('id')->all();
    $book_titles = gv(getSeedVar('book'), 'book_titles', []);

    return [
        'uuid'              => $faker->uuid(),
        'title'             => $faker->unique()->randomElement($book_titles),
        'isbn_number'       => $faker->ean8(),
        'book_author_id'    => $faker->randomElement($book_authors),
        'book_language_id'  => $faker->randomElement($book_languages),
        'book_publisher_id' => $faker->randomElement($book_publishers),
        'book_topic_id'     => $faker->randomElement($book_topics),
        'edition'           => $faker->numberBetween(1,10),
        'type'              => $faker->randomElement(['text','reference']),
        'page'              => $faker->numberBetween(80,300),
        'price'             => $faker->numberBetween(50,250),
        'summary'           => $faker->realText(50),
        'description'       => $faker->realText(100),
        'options' => []
    ];
});
