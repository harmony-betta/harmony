<?php
namespace App\Libraries;

interface Seeders {
	public function table();
	public function prepare();
	public function run();
}