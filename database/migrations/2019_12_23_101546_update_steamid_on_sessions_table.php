<?php

use App\Session;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSteamidOnSessionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$id = null;
		try {
			foreach (Session::query()->cursor() as $session) {
				$id = $session->steamid;
				$session->steamid = steamid2($id);
				$session->save();
			}
		} catch (Exception $e) {
			info("Failed to normalize SteamID $id");
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}
