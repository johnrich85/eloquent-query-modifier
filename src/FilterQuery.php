<?php namespace Johnrich85\EloquentQueryModifier;

/**
 * Class FilterQuery
 *
 * @package Johnrich85\EloquentQueryModifier
 */
class FilterQuery
{
    /**
     * The operator to filter by.
     *
     * @var string
     */
    public $operator = '=';

    /**
     * The value to filter on.
     *
     * @var string
     */
    public $value;

    /**
     * Supported operators map.
     *
     * @var array
     */
    public $supportedOperators = [
        '==' => '=',
        '=' => '=',
        '<'=> '<',
        '>'=> '>',
        '<='=> '<=',
        '>='=> '>=',
        '<>'=> '<>',
        '!='=> '!=',
        'like'=> 'like',
        'not like'=> 'not like',
        'include'=> 'include',
        'exclude'=> 'exclude'
    ];

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
     * Checks if operator valid and assigns
     * if so.
     *
     * @param $value
     * @throws \Exception
     */
    public function setOperator($value) {
        if($value == '==' || empty($value)) {
            $value = '=';
        }

        if(!in_array($value, $this->supportedOperators)) {
            throw new \Exception('Invalid operator provided.');
        }

        $this->operator = $this->supportedOperators[$value];
    }

    /**
     * @param $value
     */
    public function setValue($value) {
        $this->value = $value;
    }
}