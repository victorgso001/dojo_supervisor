<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'jkc_registry' => $faker->numerify('############'),
        'fbk_registry' => $faker->numerify('############'),
        'cbk_registry' => $faker->numerify('############'),
        'student_rg' => $faker->numerify('##########'),
        'student_cpf' => $faker->numerify('###########'),
        'name' => $faker->name,
        'birthday' => $faker->date,
        'father_name' => $faker->name,
        'mother_name' => $faker->name,
        'city_of_birth' => $faker->city,
        'state_of_birth'=> $faker->state,
        'phone' => $faker->phoneNumber,
        'address_street' => $faker->streetName,
        'address_number' => $faker->numerify('###'),
        'address_state' => $faker->state,
        'address_city' => $faker->city,
        'active' => rand(0, 1),
        'status' => rand(0, 1),
    ];
});
