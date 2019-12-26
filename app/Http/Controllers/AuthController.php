<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Invisnik\LaravelSteamAuth\SteamAuth;

class AuthController extends Controller
{

	/**
	 * The SteamAuth instance.
	 *
	 * @var SteamAuth
	 */
	protected $steam;

	/**
	 * The redirect URL.
	 *
	 * @var string
	 */
	protected $redirectURL = '/';

	/**
	 * AuthController constructor.
	 *
	 * @param SteamAuth $steam
	 */
	public function __construct(SteamAuth $steam)
	{
		$this->steam = $steam;
	}

	/**
	 * Redirect the user to the authentication page
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function redirect()
	{
		return $this->steam->redirect();
	}

	/**
	 * Get user info and log in
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function handle()
	{
		if ($this->steam->validate()) {
			$info = $this->steam->getUserInfo();

			$steamid = steamid2($info->steamID64);

			return redirect()->route('sessions.search', ['id' => $steamid]);
		}

		return $this->redirect();
	}
}
