<?php

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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'account' => $faker->unique()->safeEmail,
        'avatar_url' => $faker->imageUrl($width = 64, $height = 64),
        'nickname' => $faker->name,
        'passwd' => $faker->md5,
    ];
});

$factory->define(App\Models\UserFocus::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 1000),
        'proj_id' => $faker->numberBetween($min = 1, $max = 1000),
        'status' => $faker->numberBetween($min = 0, $max = 1),
    ];
});

$factory->define(App\Models\Token::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'symbol' => strtoupper($faker->randomLetter . $faker->randomLetter . $faker->randomLetter),
        'price' => $faker->randomFloat(6, 0, 10000)
    ];
});

$factory->define(App\Models\Project::class, function (Faker\Generator $faker) {
    return [
        'name_cn' => $faker->name,
        'name_en' => $faker->name,
        'name_short' => $faker->word,
        'banner_url' => $faker->imageUrl(600, 200),
        'logo_url' => $faker->imageUrl(100, 100),
        'title' => $faker->realText(50),
        'abstract' => $faker->realText(500, 2),
        'white_paper_url' => $faker->imageUrl(200, 200),
        'web_url' => $faker->url,
        'view_times' => $faker->numberBetween(0, 10000),
        'token_id' => $faker->unique()->numberBetween(0, 1000),
        'node_amount' => $faker->numberBetween(100, 2000),
        'total_amount' => $faker->numberBetween(0, 5000),
        'plan_amount' => $faker->numberBetween(5000, 10000),
        'start_time' => $faker->dateTimeBetween('-1 years', 'now'),
        'end_time' => $faker->dateTimeBetween('-1 years', 'now'),
        'status' => $faker->numberBetween(0, 3),
        'region' => $faker->numberBetween(1, 2),
        'bussiness_type' => $faker->numberBetween(1, 7),
        'phrase' => $faker->numberBetween(1, 4),
        'admin_id' => $faker->numberBetween(1, 1000),
        'company_tel' => $faker->e164PhoneNumber,
        'company_addr' => $faker->streetAddress,
        'company_email' => $faker->email
    ];
});

$factory->define(App\Models\ProjTag::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'tag' => $faker->word
    ];
});

$factory->define(App\Models\ProjAdvantage::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'title' => $faker->realText(50),
        'detail' => $faker->realText(500)
    ];
});

$factory->define(App\Models\ProjMember::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'photo_url' => $faker->imageUrl(200, 200),
        'name' => $faker-> name,
        'position' => $faker->word,
        'intro' => $faker->realText(500),
    ];
});

$factory->define(App\Models\ProjEvent::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'occur_time' => $faker->dateTimeBetween('-10 years', 'now'),
        'title' => $faker->realText(50),
        'detail' => $faker->realText(500)
    ];
});

$factory->define(App\Models\ProjPartner::class, function (faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'name' => $faker->name,
        'logo_url' => $faker->imageUrl(200, 200),
        'web_url' => $faker->url,
    ];
});

$factory->define(App\Models\ProjMedia::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'media_id' => $faker->numberBetween($min = 1, $max = 100),
        'image_url' => $faker->imageurl(200, 200),
        'title' => $faker->realText(50),
        'detail' => $faker->realText(500)
    ];
});

$factory->define(App\Models\ProjSocial::class, function (Faker\Generator $faker) {
    return [
        'proj_id' => $faker->numberBetween($min = 1, $max = 100),
        'social_id' => $faker->numberBetween($min = 1, $max = 100),
        'url' => $faker->url
    ];
});

$factory->define(App\Models\Social::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'logo_url' => $faker->imageurl(200, 200),
        'type' => $faker->numberBetween(0, 1)
    ];
});

$factory->define(App\Models\Media::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'logo_url' => $faker->imageurl(200, 200),
        'type' => $faker->numberBetween(0, 1)
    ];
});

