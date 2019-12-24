<?php

namespace App\Http\Controllers;

use App\Classes\SessionBuilder;
use App\Http\Requests\SessionSearchRequest;
use App\Session;
use Exception;

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

	public function raw(Session $session)
	{
		$builder = new SessionBuilder($session);
		$data = $builder->toArray()->toArray();

		dd($data);
	}

	public function search(SessionSearchRequest $request)
	{
		$id = steamid2($request->input('id'));

		$sessions = Session::with('server')->where('steamid', $id)->orderBy('created_at', 'DESC')->get();

		return view('search', compact('sessions'));
	}

	public function random()
	{
		$session = Session::query()->inRandomOrder(254)->first(['id']);

		if (!$session)
			throw new Exception('It seems like the database is empty');

		return redirect()->route('sessions.show', $session);
	}
}
