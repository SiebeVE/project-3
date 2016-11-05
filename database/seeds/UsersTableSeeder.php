<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run () {
		DB::table('users')->insert([
			'firstname'      => "Siebe",
			'lastname'       => "Vanden Eynden",
			'street'         => "Den Eeckhofstraat",
			'number'         => "32",
			'postal'         => 2650,
			'city'           => "Edegem",
			'country'        => "Belgium",
			'email'          => "siebe@siebeve.be",
			'password'       => bcrypt('wachtwoord'),
			'remember_token' => "3iTa7vSjzblcbjZ8trwDInS8IjKn3DVqWnZtU6ZHHYT0WSjNk6Aj4UaQhDWp",
		]);
	}
}
