<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

use Johnrich85\EloquentQueryModifier\InputConfig;

class FilterModifier extends BaseModifier
{
    /**
     * The type of filter that will be applied.
     * @var string
     */
    protected $filterType = 'where';

    /**
     * @var bool
     */
    protected $first = true;

    /**
     * FilterModifier constructor.
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param InputConfig $config
     */
    public function __construct(array $data, \Illuminate\Database\Eloquent\Builder $builder, InputConfig $config) {
        parent::__construct($data, $builder, $config);

        $this->filterType = $this->config->getFilterType();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function modify()
    {
        $fields = $this->getFilterableFields();

        if ($fields === false) {
            return $this->builder;
        } else {
            if ($fields == '') {
                $this->throwNoDataException();
            }
        }

        foreach ($fields as $field) {
            if (empty($this->data[$field])) {
                continue;
            }

            $data = $this->data[$field];

            if (is_array($data)) {
                $this->addWhereFilters($field, $data);
                continue;
            }

            $this->addWhereFilter($field, $data);
        }

        return $this->builder;
    }

    /**
     * Returns an array of filterable fields,
     * or false if none are found.
     *
     * @return array|bool
     */
    protected function getFilterableFields()
    {
        $fields = $this->config->getFilterableFields();

        if (count($fields) == 0) {
            return false;
        }

        return $fields;
    }

    /**
     * @param $field
     * @param $value
     * @throws \Exception
     */
    protected function addWhereFilter($field, $value)
    {
        $operator = '=';

        $json = $this->jsonDecode($value);

        if ($json) {
            $value = $this->getJsonValue($json);
            $operator = $this->getJsonOperator($json);
        }

        if ($value !== null) {
            $this->addWhereType($field, $operator, $value);
        } else {
            $error = "Invalid data supplied via $field parameter. Please supply valid 'value' and 'operator'.";
            throw new \Exception($error);
        }
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     */
    protected function addWhereType($field, $operator, $value)
    {
        if ($this->isInclude($operator)) {
            $this->builder = $this->builder->whereIn($field, $value);
            return;
        } elseif ($this->isExclude($operator)) {
            $this->builder = $this->builder->whereNotIn($field, $value);
        } else {
            $this->addStandardWhere($field, $operator, $value);
        }
    }

    /**
     * Adds standard where filter.
     *
     * @param $field
     * @param $operator
     * @param $value
     */
    protected function addStandardWhere($field, $operator, $value)
    {
        if ($this->filterType == 'orWhere' && !$this->first) {
            $this->builder = $this->builder->orWhere($field, $operator, $value);
        } else {
            $this->builder = $this->builder->where($field, $operator, $value);
            $this->first = false;
        }
    }

    /**
     * @param $operator
     * @return bool
     */
    protected function isInclude($operator)
    {
        if ($operator == 'include') {
            return true;
        }

        return false;
    }

    /**
     * @param $operator
     * @return bool
     */
    protected function isExclude($operator)
    {
        if ($operator == 'exclude') {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function jsonDecode($value)
    {
        if (!is_string($value)) {
            return false;
        }

        $json = json_decode($value, true);

        $isJson = is_array($json) && (json_last_error() == JSON_ERROR_NONE);

        if (!$isJson) {
            return false;
        }

        return $json;
    }

    /**
     * @param array $decoded
     * @return mixed|null
     */
    protected function getJsonValue(array $decoded)
    {
        if (isset($decoded['value'])) {
            return $decoded['value'];
        }

        return null;
    }

    /**
     * @param array $decoded
     * @return mixed|null
     */
    protected function getJsonOperator(array $decoded)
    {
        if (isset($decoded['operator'])) {
            return $decoded['operator'];
        }

        return null;
    }

    /**
     * Loops over an array, adding a where
     * filter on each iteration.
     *
     * @param $field
     * @param array $data
     */
    protected function addWhereFilters($field, array $data)
    {
        foreach ($data as $fieldValue) {
            $this->addWhereFilter($field, $fieldValue);
        }
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

}
