<?php

use App\User;
use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;

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
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verify = $faker->randomElement([
            User::VERIFIED, User::NOT_VERIFIED
        ]),
        'verification_token' => ($verify == User::VERIFIED)
            ? null
            : User::generateVerificationToken(),
        'admin' => $faker->randomElement([User::IS_ADMIN, User::NOT_ADMIN]),
    ];
});

$factory->define(Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

$factory->define(Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([
            Product::AVAILABLE, Product::NOT_AVAILABLE
        ]),
        'image' => $faker->imageUrl($width = 128, $height = 128),
        // 'seller_id' => User::inRandomOrder()->first()->id,
        'seller_id' => User::all()->random()->id,
    ];
});

$factory->define(Transaction::class, function (Faker\Generator $faker) {

    $seller = Seller::has('products')->get()->random();
    $buyer  = User::all()->except($seller->id)->random();

    return [
        'quantity' => $faker->numberBetween(1, 6),
        'buyer_id' => $buyer->id,
        'product_id' => $seller->products->random()->id,
    ];
});
