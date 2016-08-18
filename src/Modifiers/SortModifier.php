<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

class SortModifier extends BaseModifier
{

    /**
     * Sort order.
     *
     * @var string
     */
    protected $order = 'ASC';

    /**
     * The sort string pulled from the
     * query string.
     *
     * @var String
     */
    protected $sortString;

    /**
     * Adds sorting to query-builder (if data
     * provided contains sort info)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify()
    {
        $this->sortString = $this->fetchValuesFromData();

        if ($this->sortString == false) {
            return $this->builder;
        }

        $this->parseSortOrder();

        return $this->addSortToQueryBuilder();
    }

    /**
     * Parses the sort order from the
     * query string.
     *
     */
    protected function parseSortOrder()
    {
        $firstChar = substr($this->sortString, 0, 1);

        $this->order = $this->symbolToOrder($firstChar);

        $this->sortString = $this->removeOrderingFromField($this->sortString);
    }

    /**
     * @param $field
     * @return mixed
     */
    protected function removeOrderingFromField($field)
    {
        $firstChar = substr($field, 0, 1);

        if ($firstChar == '-' || $firstChar == '+') {
            $field = substr($field, 1);
        }

        return trim($field);
    }

    /**
     * Given a string, returns either ASC
     * or DESC
     *
     * @param $char
     * @return string
     */
    protected function symbolToOrder($char)
    {
        if ($char == '-') {
            return 'DESC';
        } else {
            if ($char == '+') {
                return 'ASC';
            } else {
                return 'ASC';
            }
        }
    }

    /**
     * Gets the sort values from the data
     * provided. Returns false if not
     * found.
     *
     * @return bool|array
     */
    protected function fetchValuesFromData()
    {
        $sortIndex = $this->config->getSort();

        if (!isset($this->data[$sortIndex])) {
            return false;
        }

        return $this->data[$sortIndex];
    }

    /**
     * Adds sorting to query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    protected function addSortToQueryBuilder()
    {
        $fields = $this->listToArray($this->sortString);
        $allowedFields = $this->config->getFilterableFields();

        foreach ($fields as $field) {
            $field = $this->removeOrderingFromField($field);

            if (!empty($allowedFields[$field])) {
                $this->builder = $this->builder->orderBy($field, $this->order);
                continue;
            }
        }

        return $this->builder;
    }

    /**
     * @return String
     */
    public function getSortString()
    {
        return $this->sortString;
    }

    /**
     * @param String $sortString
     */
    public function setSortString($sortString)
    {
        $this->sortString = $sortString;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }


}