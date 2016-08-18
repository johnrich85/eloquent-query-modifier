<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEqmBooksTable extends Migration {

	public function up()
	{
		Schema::create('books', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->integer('bookable_id')->nullable();
			$table->string('bookable_type')->nullable();
			$table->integer('theme_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('books');
	}
}