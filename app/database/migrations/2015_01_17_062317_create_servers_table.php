<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('servers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('ip');
			$table->string('password');
			$table->timestamps();
		});

		DB::table('servers')->insert(
			array(
				array (
					'id' => '1',
					'name' => 'server1',
					'ip' => '127.0.0.1',
					'password' => 'password'
				),
				array (
					'id' => '2',
					'name' => 'server2',
					'ip' => '255.255.255.255',
					'password' => 'password'
				)
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('servers');
	}

}
