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