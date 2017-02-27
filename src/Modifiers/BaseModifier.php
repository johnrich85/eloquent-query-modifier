<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Johnrich85\EloquentQueryModifier\FilterSubQuery;
use Johnrich85\EloquentQueryModifier\InputConfig;
use Johnrich85\EloquentQueryModifier\Modifiers\Contract\EqmCanModify;

/**
 * Class BaseModifier
 *
 * @package Johnrich85\EloquentQueryModifier\Modifiers
 */
abstract class BaseModifier implements EqmCanModify
{

    /**
     * @var InputConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * BaseModifier constructor.
     *
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param InputConfig $config
     */
    public function __construct(array $data, \Illuminate\Database\Eloquent\Builder $builder, InputConfig $config)
    {
        $this->data = $data;
        $this->builder = $builder;
        $this->config = $config;
    }

    /**
     * Decodes json if possible, else explodes string.
     *
     * @param $fields
     * @return array|mixed|trim
     */
    protected function parseString($fields)
    {
        $json = json_decode($fields, true);

        if ($json != null) {
            return $json;
        }

        return $this->commaListToArray($fields);
    }

    /**
     * Transforms comma separated list to array.
     *
     * @param $fields
     * @return array
     */
    protected function commaListToArray($fields)
    {
        $payload = [];

        $names = explode(',', $fields);

        foreach($names as $relationName) {
            $payload[trim($relationName)] = [];
        }

        return $payload;
    }

    /**
     * Given a comma delimited string, explodes
     * to array.
     *
     * @param $list
     * @return array
     */
    protected function listToArray($list)
    {
        $payload = array_map(
            'trim',
            explode(',', $list)
        );

        return $payload;
    }


    /**
     * @param $data
     * @param $queryBuilder
     * @param $config
     * @return FilterModifier
     */
    protected function buildFilterModifier($data, $queryBuilder, $config)
    {
        return $modifier = new FilterModifier($data, $queryBuilder, $config);
    }

    /**
     * Returns sub query in format that can be injected
     * into Modifiers.
     *
     * @param FilterSubQuery $subQuery
     * @return array
     */
    protected function subQueryToArray(FilterSubQuery $subQuery)
    {
        return [
            $subQuery->column => [
                'operator' => $subQuery->operator,
                'value' => $subQuery->value
            ]
        ];
    }

    /**
     * Builds a sub query using an instance
     * of FilterModifier.
     *
     * @param $query
     * @return \Closure
     */
    protected function buildSubQuery($name, $query)
    {
        if (empty($query['callback']) || !is_callable($query['callback'])) {
            $subQuery = new FilterSubQuery($query);

            if (!$subQuery->validate()) {
                $this->throwInvalidSubQueryException($name);
            }

            $data = $this->subQueryToArray($subQuery);

            $query = function ($q) use ($data) {
                if(!$q instanceof Builder) {
                    $q = $q->getQuery();
                }

                $modifier = $this->buildFilterModifier($data, $q, $this->config);

                $modifier->modify($q);
            };
        } else {
            $query = $query['callback'];
        }

        return $query;
    }

    /**
     * @return bool
     */
    protected function hasEagerLoad()
    {
        return $hasEagerLoad = (boolean)count($this->builder->getEagerLoads());
    }

    /**
     * @throws \Exception
     */
    protected function throwNoDataException()
    {
        throw new \Exception('Query parameter provided, but contains no data.');
    }

    /**
     * @param $field
     * @throws \Exception
     */
    protected function throwInvalidFieldException($field)
    {
        throw new \Exception('Query string parameter contains an invalid field: ' . $field);
    }

    /**
     * @param $name
     * @throws \Exception
     */
    protected function throwInvalidRelationException($name)
    {
        throw new \Exception('The ' . $name . ' relation does not exist.');
    }

    /**
     * @param $name
     * @throws \Exception
     */
    protected function throwInvalidSubQueryException($name)
    {
        throw new \Exception('The parameters provided for ' . $name . ' are invalid. Please ensure a column and value are provided.');
    }

    /**
     * @param $class
     * @throws \Exception
     */
    protected function throwSearchNotSupportedException($class)
    {
        $message = $class;
        $message .= ' does not support search. ';
        $message .= 'To enable search for this model implement the Sofa\Eloquence\Eloquence trait';

        throw new \Exception($message);
    }
}