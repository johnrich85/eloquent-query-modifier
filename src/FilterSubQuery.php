<?php namespace Johnrich85\EloquentQueryModifier;

/**
 * Class FilterQuery
 *
 * @package Johnrich85\EloquentQueryModifier
 */
class FilterSubQuery
{
    /**
     * @var string
     */
    public $column = '';

    /**
     * @var string
     */
    public $operator = '=';

    /**
     * @var string
     */
    public $value = '';

    /**
     * FilterSubQuery constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if(isset($values['column'])) {
            $this->setColumn($values['column']);
        }

        if(isset($values['operator'])) {
            $this->setOperator($values['operator']);
        }

        if(isset($values['value'])) {
            $this->setValue($values['value']);
        }
    }

    /**
     * @param $value
     */
    public function setColumn($value) {
        $this->column = $value;
    }

    /**
     * @param $value
     */
    public function setOperator($value) {
        if($value == '==') {
            $value = '=';
        }

        $this->operator = $value;
    }

    /**
     * @param $value
     */
    public function setValue($value) {
        $this->value = $value;
    }
}