<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessions', function (Blueprint $table) {
			$table->bigIncrements('id');

			$table->string('steamid');
			$table->unsignedInteger('server_id')->references('id')->on('servers');

			$table->mediumText('metrics')->nullable();

			$table->timestamp('closed_at')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sessions');
	}
}
