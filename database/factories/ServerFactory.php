<?php

/** @var Factory $factory */

use App\Server;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Server::class, function (Faker $faker) {
    return [
    	'address' => $faker->ipv4,
    ];
});
