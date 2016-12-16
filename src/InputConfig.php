<?php namespace Johnrich85\EloquentQueryModifier;

use Illuminate\Support\Facades\DB;
use Johnrich85\EloquentQueryModifier\Modifiers\FieldSelectionModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\HasModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\PagingModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\SortModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\WithModifier;

class InputConfig {

    /**
     * Name of the sort parameter.
     *
     * @var string
     */
    protected $sort = 'sort';

    /**
     * Name of the parameter containing
     * field names.
     *
     * @var string
     */
    protected $fields = 'fields';

    /**
     * Contains a list of fields to be filtered
     * against.
     *
     * @var array
     */
    protected $filterableFields = array();

    /**
     * The name of the limit parameter.
     * @var
     */
    protected $limit = 'limit';

    /**
     * The name of the search parameter.
     *
     * @var string
     */
    protected $search = 'q';

    /**
     * The mode of search.
     *
     * @var string[wildcard|literal]
     */
    protected $searchMode = 'column_limited';

    /**
     * The name of the page parameter.
     *
     * @var string
     */
    protected $page = 'page';

    /**
     * The name of the with parameter.
     *
     * @var string
     */
    protected $with = 'with';

    /**
     * The name of the has parameter.
     *
     * @var string
     */
    protected $has = 'has';

    /**
     * @var string
     */
    protected $filterType = 'andWhere';

    /**
     * List of supported modifier objects.
     *
     * @var array
     */
    protected $modifiers = array(
        WithModifier::class,
        HasModifier::class,
        FieldSelectionModifier::class,
        FilterModifier::class,
        SortModifier::class,
        PagingModifier::class,
        SearchModifier::class
    );

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }


    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getFilterableFields()
    {
        return $this->filterableFields;
    }

    /**
     * @return array
     */
    public function getModifiers() {
        return $this->modifiers;
    }

    /**
     * @param $modifierName
     */
    public function addModifier($modifierName) {
        $this->modifiers[] = $modifierName;
    }

    /**
     * @param $modifierName
     */
    public function removeModifier($modifierName) {
        $key = array_search($modifierName, $this->modifiers);

        if ($key !== false) {
            unset($this->modifiers[$key]);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * @param string $filterType
     */
    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @return string
     */
    public function getSearchMode()
    {
        return $this->searchMode;
    }

    /**
     * @param string $searchMode
     */
    public function setSearchMode($searchMode)
    {
        $this->searchMode = $searchMode;
    }

    /**
     * @return string
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * @param string $with
     */
    public function setWith($with)
    {
        $this->with = $with;
    }

    /**
     * @return string
     */
    public function getHas()
    {
        return $this->has;
    }

    /**
     * @param string $with
     */
    public function setHas($has)
    {
        $this->has = $has;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public function setFilterableFields(\Illuminate\Database\Eloquent\Builder $builder)
    {
        $table = $builder->getModel()->getTable();

        $columns = DB::getSchemaBuilder()->getColumnListing($table);

        foreach ($columns as $col) {
            $this->filterableFields[$col] = $col;
        }
    }
}
