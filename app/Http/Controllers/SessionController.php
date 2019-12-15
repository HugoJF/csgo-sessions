<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SessionController extends Controller
{
	public function index()
	{
		$sessions = Session::all();

		return view('sessions', compact('sessions'));
	}

	public function show(Session $session)
	{
		$keys = collect(Redis::command('keys', ["$session->id.*"]));

		$data = $keys->mapWithKeys(function ($key) use ($session) {
			$value = Redis::connection('input')->get($key);
			return [$key => $value];
		});

		return view('session', compact('session', 'data'));
	}
}
