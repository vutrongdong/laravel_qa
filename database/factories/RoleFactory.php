<?php

use Faker\Generator as Faker;

$factory->define(App\Repositories\Roles\Role::class, function (Faker $faker) {
    return [
    	'name' => $faker->name,
        'slug' => $faker->slug,
        'permissions' => []
    ];
});
