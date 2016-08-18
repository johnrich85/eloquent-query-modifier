<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEqmAuthorsTable extends Migration {

	public function up()
	{
		Schema::create('authors', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->integer('city_id')
                ->unsigned()
                ->nullable();
		});
	}

	public function down()
	{
		Schema::drop('authors');
	}
}