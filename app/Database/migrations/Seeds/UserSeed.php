<?php
namespace App\Database\Seeds;

use App\Libraries\Seeders;

/**
 * UserSeed
 */
class UserSeed implements Seeders
{
	public function table()
	{
		return 'users';
	}

	public function prepare()
	{
		$user_data = [
			[
				'username' => 'admin',
				'email' => 'admin@email.com',
				'full_name' => 'Abimana',
				'address' => 'Magelang',
				'phone_number' => '082132132213',
				'role' => 'admin',
				'password' => password_hash('admin', PASSWORD_DEFAULT)
			],
			[
				'username' => 'kasir',
				'email' => 'kasir@email.com',
				'full_name' => 'Zulaikha',
				'address' => 'Magelang',
				'phone_number' => '0812321421412',
				'role' => 'kasir',
				'password' => password_hash('kasir', PASSWORD_DEFAULT)
			]
		];
		return $user_data;
	}

	public function run()
	{
		return [ 'table' => $this->table(), 'fields' => $this->prepare() ];
	}
}