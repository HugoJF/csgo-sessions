<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
	public function index()
	{
		$sessions = Session::all();

		return view('sessions', compact('sessions'));
	}
}
