<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {

    protected $fillable = [
        'name'
    ];

	/**
	 * Morph To relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\morphTo
	 */
	public function bookable()
	{
		return $this->morphTo();
	}

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function theme()
	{
		return $this->belongsTo('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Theme');
	}

	/**
	 * Many to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\belongToMany
	 */
	public function authors()
	{
		return $this->belongsToMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Author');
	}

}