<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->string('mask');
			$table->string('reason');
			$table->string('action');
			$table->timestamps();
		});

		DB::table('bans')->insert(
			array(
				array (
					'id' => '1',
					'type' => 'nick',
					'mask' => '*C*h*a*n*S*e*r*v*',
					'reason' => 'Reserved for Services',
					'action' => 'kill'
				),
				array (
					'id' => '2',
					'type' => 'ip',
					'mask' => '195.86.232.81',
					'reason' => 'Delinked server',
					'action' => 'kill'
				),
				array (
					'id' => '3',
					'type' => 'server',
					'mask' => 'eris.berkeley.edu',
					'reason' => 'Get out of here.',
					'action' => 'kill'
				),
				array (
					'id' => '4',
					'type' => 'user',
					'mask' => '*tirc@*.saturn.bbn.com',
					'reason' => 'Idiot',
					'action' => 'gline'
				),
				array (
					'id' => '5',
					'type' => 'realname',
					'mask' => 'Swat Team',
					'reason' => 'mIRKFORCE',
					'action' => 'gline'
				),
				array (
					'id' => '6',
					'type' => 'realname',
					'mask' => 'sub7server',
					'reason' => 'sub7',
					'action' => 'kill'
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
		Schema::drop('bans');
	}

}
