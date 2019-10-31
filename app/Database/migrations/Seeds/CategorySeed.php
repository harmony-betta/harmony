<?php
namespace App\Database\Seeds;

use App\Libraries\Seeders;

/**
 * CategorySeed
 */
class CategorySeed implements Seeders
{
	public function table()
	{
		return 'categories';
	}

	public function prepare()
	{
		return [
			[
				'code' => 'KTG0001',
				'name' => 'Minuman'
			],
			[
				'code' => 'KTG0002',
				'name' => 'Rokok'
			],
			[
				'code' => 'KTG0003',
				'name' => 'Bumbu Dapur'
			],
			[
				'code' => 'KTG0004',
				'name' => 'Roti'
			],
			[
				'code' => 'KTG0005',
				'name' => 'Snack'
			]
		];
	}

	public function run()
	{
		return [ 'table' => $this->table(), 'fields' => $this->prepare() ];
	}
}