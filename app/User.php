<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'firstname',
		'lastname',
		'email',
		'password',
		'street',
		'number',
		'postal',
		'city',
		'country'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

    // Check if the users information is complete or not
    public function isComplete() {
        if(empty($this->attributes['name']) || empty($this->attributes['street'])) return false;
        return true;
    }

    public function getFullNameAttribute() {
        return $this->attributes['firstname'] . " " . $this->attributes['lastname'];
    }


	// Filler for renamed function
	public function book () {
		return $this->books();
	}

	public function books () {
		return $this->belongsToMany(Book::class)->withPivot(["type", "condition", "status", "id", "price"]);
	}

	public function getAddress () {
		return $this->street . " " . $this->number . ", " . $this->postal . " " . $this->city . ", " . $this->country;
	}
}
