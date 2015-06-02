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

	public function getFullName()
	{
		if (!$this->first_name || !$this->last_name) {
			return null;
		}
		return "{$this->first_name} {$this->last_name}";
	}

	public function getFullNameOrUsername()
	{
		return $this->getFullName() ?: $this->username;
	}
}