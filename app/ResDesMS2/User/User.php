<?php

namespace ResDesMS2\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

Class User extends Eloquent
{
	protected $table = 'users';
	protected $fillable = [
		'email',
		'username',
		'password',
		'active',
		'active_hash',
		'remember_ident',
		'remember_token',
	];
}