<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReservationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('people');
			$table->integer('users_id')->unsigned()->index('fk_reservations_users_idx');
			$table->integer('movies_id')->index('fk_reservations_movies1_idx');
			$table->date('reservation_date');
			$table->text('positions', 65535);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reservations');
	}

}
