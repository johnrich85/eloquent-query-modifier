<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

	/**
	 * The fillable attributes.
	 *
	 * @var string
	 */
	public $fillable = ['name', 'country_id'];

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country()
	{
		return $this->belongsTo('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Country');
	}

	/**
	 * Has Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function authors()
	{
		return $this->hasMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Author');
	}

}