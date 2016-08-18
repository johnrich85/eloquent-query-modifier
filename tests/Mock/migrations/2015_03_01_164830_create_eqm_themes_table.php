<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEqmThemesTable extends Migration {

	public function up()
	{
		Schema::create('themes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
		});
	}

	public function down()
	{
		Schema::drop('themes');
	}
}