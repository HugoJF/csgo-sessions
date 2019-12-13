<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
	protected $fillable = ['address'];

	public function sessions()
	{
		return $this->hasMany(Session::class);
	}
}
