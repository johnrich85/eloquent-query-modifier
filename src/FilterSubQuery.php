<?php namespace Johnrich85\EloquentQueryModifier;

/**
 * Class FilterQuery
 *
 * @package Johnrich85\EloquentQueryModifier
 */
class FilterSubQuery extends FilterQuery
{
    /**
     * @var string
     */
    public $column = '';

    /**
     * FilterSubQuery constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        if(isset($values['column'])) {
            $this->setColumn($values['column']);
        }
    }

    /**
     * @param $value
     */
    public function setColumn($value) {
        $this->column = $value;
    }

    /**
     * Determines whether or not the query
     * is valid.
     *
     * @return bool
     */
    public function validate()
    {
        if(empty($this->column) || $this->value == null) {
           return false;
        }

        return true;
    }
}