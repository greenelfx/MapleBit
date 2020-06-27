<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->text();

    return [
        'title' => $title,
        'content' => $faker->paragraph(),
        'category' => 'some-category',
        'locked' => false,
        'slug' => Str::slug($title).'-'.Hashids::encode(Carbon::now()->timestamp), // generate slugs like prod
    ];
});
