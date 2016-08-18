<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;

class Editor extends Model {

	/**
	 * The fillable attributes.
	 *
	 * @var string
	 */
	public $fillable = ['name'];

	/**
	 * Has One Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasOne
	 */
	public function contact()
	{
		return $this->hasOne('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Contact');
	}

	/**
	 * Morph Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\morphMany
	 */
	public function books()
	{
		return $this->morphMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Book', 'bookable');
	}

}