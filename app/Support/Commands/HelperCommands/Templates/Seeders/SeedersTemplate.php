<?php
namespace App\Database\Seeds;

use App\Libraries\Seeders;

class SeedersTemplate implements Seeders
{
	/**
	 *
	 *  DON'T REMOVE THIS LINE
	 * 
	 * capture your seed table
	 * @return string  	table name
	 */
	public function table()
	{
		return 'define_your_table_name';
	}

	/**
	 *
	 *  DON'T REMOVE THIS LINE
	 *
	 * Capture all field in table and defined your values
	 * @return array  inserted data to table
	 */
	public function prepare()
	{
		return [
			[
				// ...
			],
			// ....
		];
	}

	/**
	 * 
	 * DON'T REMOVE THIS LINE
	 * 
	 */
	public function run()
	{
		return [ 'table' => $this->table(), 'fields' => $this->prepare() ];
	}
}