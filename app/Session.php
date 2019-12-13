<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function server()
	{
		return $this->belongsTo(Server::class);
	}
}
