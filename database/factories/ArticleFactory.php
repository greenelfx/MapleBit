<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->text();
    return [
        'title' => $title,
        'content' => $faker->paragraph(),
        'category' => 'some-category',
        'slug' => Str::slug($title).'-'.Hashids::encode(Carbon::now()->timestamp), // generate slugs like prod
    ];
});
