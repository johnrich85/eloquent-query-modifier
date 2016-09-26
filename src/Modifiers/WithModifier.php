<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

use Johnrich85\EloquentQueryModifier\FilterSubQuery;

/**
 * Class WithModifier
 * @package Johnrich85\EloquentQueryModifier\Modifiers
 */
class WithModifier extends BaseModifier
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify()
    {
        $eagerLoads = $this->fetchValuesFromData();

        $isEmptyArray = is_array($eagerLoads) && count($eagerLoads) == 0;

        if ($eagerLoads == null || $eagerLoads === false || $isEmptyArray) {
            return $this->builder;
        }

        $this->addEagerLoads($eagerLoads);

        return $this->builder;
    }

    /**
     * Adds eager loads. Throws exception if
     * invalid relation provided.
     *
     * @param $eagerLoads
     */
    protected function addEagerLoads($eagerLoads)
    {
        foreach($eagerLoads as $name=>$query) {
            try {
                $this->builder->getRelation($name);
            } catch(\BadMethodCallException $e) {
                $this->throwInvalidRelationException($name);
            }

            $this->addEagerLoad($name, $query);
        }
    }

    /**
     * @param $name
     * @param array|closure $query
     */
    protected function addEagerLoad($name, $query)
    {
        if(count($query) == 0) {
            $this->builder->with($name);
        } else {
            if(!is_callable($query)) {
                $subQuery = new FilterSubQuery($query);

                $query = function($q) use ($subQuery) {
                    $q->where($subQuery->column, $subQuery->operator,  $subQuery->value);
                };
            }

            $this->builder->with([$name => $query]);
        }
    }

    /**
     * @return array|bool
     */
    public function fetchValuesFromData()
    {
        $withParameter = $this->config->getWith();

        if (empty($this->data[$withParameter])) {
            return false;
        }

        $fields = $this->data[$withParameter];

        if(!is_array($fields)) {
            $fields = json_decode($fields, true);
        }

        return $fields;
    }
}
