<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

use Johnrich85\EloquentQueryModifier\FilterCountQuery;
use Johnrich85\EloquentQueryModifier\FilterQuery;
use Johnrich85\EloquentQueryModifier\FilterSubQuery;

/**
 * Class WithModifier
 * @package Johnrich85\EloquentQueryModifier\Modifiers
 */
class HasModifier extends BaseModifier
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify()
    {
        $hasQueries = $this->fetchValuesFromData();

        $isEmptyArray = is_array($hasQueries) && count($hasQueries) == 0;

        if ($hasQueries == null || $hasQueries === false || $isEmptyArray) {
            return $this->builder;
        }

        $this->addHasFilters($hasQueries);

        return $this->builder;
    }

    /**
     * @param array $hasQueries
     */
    protected function addHasFilters($hasQueries)
    {
        foreach($hasQueries as $name=>$query) {
            try {
                $this->builder->getRelation($name);
            } catch(\BadMethodCallException $e) {
                $this->throwInvalidRelationException($name);
            }

            $this->addHasFilter($name, $query);
        }
    }

    /**
     * @param $name
     * @param array|closure $query
     */
    protected function addHasFilter($name, $query)
    {
        $countQuery = $this->pluckCountQuery($query);

        if(count($query) == 0) {
            $this->builder->has($name, $countQuery->operator, (int) $countQuery->value);
        } else {
            $query = $this->buildSubQuery($query);

            $this->builder->whereHas($name, $query, $countQuery->operator, (int) $countQuery->value);
        }
    }

    /**
     * @param $query
     * @return \Closure
     */
    protected function buildSubQuery($query)
    {
        if(empty($query['callback']) || !is_callable($query['callback'])) {
            $subQuery = new FilterSubQuery($query);

            $query = function($q) use ($subQuery) {
                $q->where($subQuery->column, $subQuery->operator,  $subQuery->value);
            };
        } else {
            $query = $query['callback'];
        }

        return $query;
    }


    /**
     * @param $query
     * @return FilterCountQuery|Mockery_0_Illuminate_Database_Eloquent_Builder
     */
    protected function pluckCountQuery(&$query)
    {
        if(empty($query['count'])) {
            $values = [];
        } else {
            $values = $query['count'];
        }

        $countQuery = new FilterCountQuery($values);

        unset($query['count']);

        return $countQuery;
    }

    /**
     * @return array|bool
     */
    public function fetchValuesFromData()
    {
        $withParameter = $this->config->getHas();

        if (empty($this->data[$withParameter])) {
            return false;
        }

        $fields = $this->data[$withParameter];

        if(!is_array($fields) ) {
            $fields = json_decode($fields, true);
        }

        return $fields;
    }
}