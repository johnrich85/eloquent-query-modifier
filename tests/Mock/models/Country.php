<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

	/**
	 * The fillable attributes.
	 *
	 * @var string
	 */
	public $fillable = ['name'];

	/**
	 * Has Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function cities()
	{
		return $this->hasMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\City');
	}

	/**
	 * Has Many Through relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasManyThrough
	 */
	public function authors()
	{
		return $this->hasManyThrough('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Author', 'Johnrich85\EloquentQueryModifier\Tests\Mock\Models\City');
	}

}