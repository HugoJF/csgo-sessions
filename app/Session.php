<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
	protected $dates = ['closed_at'];

	public function getDurationAttribute()
	{
		return $this->created_at->diffInMinutes($this->closed_at);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function server()
	{
		return $this->belongsTo(Server::class);
	}
}
