<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

	public $fillable = ['name', 'city_id'];

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function city()
	{
		return $this->belongsTo('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\City');
	}

	/**
	 * Many to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\belongToMany
	 */
	public function books()
	{
		return $this->belongsToMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Book');
	}

}