<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'password' => $faker->password,
        'user' => $faker->name,
        'token' => md5(\Str::random(60)),
    ];
});
