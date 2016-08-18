<?php namespace Johnrich85\EloquentQueryModifier\Tests\Mock\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Category extends Model {

    use Eloquence;

    /**
     * @var array
     */
    protected $searchableColumns = ['name'];

	/**
	 * The fillable attributes.
	 *
	 * @var string
	 */
	public $fillable = ['name'];

	/**
	 * Morph Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\morphToMany
	 */
	public function themes()
	{
		return $this->morphToMany('Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Theme', 'themable');
	}

    public function book()
    {
        return $this->morphOne(Book::class, 'bookable');
    }

}