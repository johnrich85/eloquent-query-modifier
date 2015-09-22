<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

class SortModifier extends BaseModifier {

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
    public function modify() {

        $this->sortString = $this->fetchValuesFromData();

        if($this->sortString === false) {
            return $this->builder;
        }
        else if($this->sortString == '') {
            $this->throwNoDataException();
        }

        $this->parseSortOrder();

        return $this->addSortToQueryBuilder();
    }

    /**
     * Parses the sort order from the
     * query string.
     *
     */
    protected function parseSortOrder() {
        $firstChar = substr($this->sortString,0,1);
        $this->order = $this->symbolToOrder($firstChar);

        if($firstChar == '-' || $firstChar == '+') {
            $this->sortString = substr($this->sortString, 1);
        }
    }

    /**
     * Given a string, returns either ASC
     * or DESC
     *
     * @param $char
     * @return string
     */
    protected function symbolToOrder($char) {
        if($char == '-') {
            return 'DESC';
        }
        else if($char == '+') {
            return 'ASC';
        }else {
            return 'ASC';
        }
    }

    /**
     * Gets the sort values from the data
     * provided. Returns false if not
     * found.
     *
     * @return bool|array
     */
    protected function fetchValuesFromData() {
        $sortIndex = $this->config->getSort();

        if(!isset($this->data[$sortIndex])) {
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
    protected function addSortToQueryBuilder() {
        $fields = $this->listToArray($this->sortString);
        $allowedFields = $this->config->getFilterableFields();

        foreach($fields as $field) {
            $field = trim($field);
            if(empty($allowedFields[$field])) {
                $this->throwInvalidFieldException($field);
            }
            $this->builder = $this->builder->orderBy($field, $this->order);
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