<?php

namespace App\Http\Controllers;

use App\Classes\SessionBuilder;
use App\Http\Requests\SessionSearchRequest;
use App\Session;

class SessionController extends Controller
{
	public function index()
	{
		return redirect()->route('search');
	}

	public function show(Session $session)
	{
		$builder = new SessionBuilder($session);
		$data = $builder->toArray()->toArray();

		return view('session', compact('session', 'data'));
	}

	public function search(SessionSearchRequest $request)
	{
		$id = steamid2($request->input('id'));

		$sessions = Session::with('server')->where('steamid', $id)->orderBy('created_at', 'DESC')->get();

		return view('search', compact('sessions'));
	}

	public function random()
	{
		$session = Session::query()->inRandomOrder()->first(['id']);

		return redirect()->route('sessions.show', $session);
	}
}
