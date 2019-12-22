<?php

namespace App\Http\Controllers;

use App\Classes\SessionBuilder;
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
		$builder = new SessionBuilder($session);
		$data = $builder->toArray()->toArray();

		return view('session', compact('session', 'data'));
	}
}
