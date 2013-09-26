<?php

use Illuminate\Database\Migrations\Migration;

class InstallCart extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('laravel_cart', function($table) {
			$table->increments('id');
			$table->string('session_id', 255);
		    $table->integer('model_id');
			$table->string('model', 100);
			$table->integer('quantity')->default(1);
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
		Schema::drop('laravel_cart');	
	}

}