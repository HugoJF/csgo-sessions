<?php

/** @var Factory $factory */

use App\Server;
use App\Session;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Session::class, function (Faker $faker) {
	return [
		'steamid'   => 'STEAM_0:0:12345',
		'server_id' => Server::query()->inRandomOrder()->first(),
	];
});

$factory->state(Session::class, 'active', [
	'active' => true,
]);

$factory->state(Session::class, 'inactive', [
	'active' => false,
]);