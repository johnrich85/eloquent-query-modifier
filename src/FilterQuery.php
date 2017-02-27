<?php namespace Johnrich85\EloquentQueryModifier;

/**
 * Class FilterQuery
 * @package Johnrich85\EloquentQueryModifier
 */
class FilterQuery
{
    /**
     * @var string
     */
    public $operator = '=';

    /**
     * @var string
     */
    public $value;

    /**
     * FilterQuery constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $operator = isset($values['operator']) ? $values['operator'] : null;

        $this->setOperator($operator);

        if(isset($values['value'])) {
            $this->setValue($values['value']);
        }
    }


    /**
     * @param $value
     */
    public function setOperator($value) {
        if($value == '==' || empty($value)) {
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