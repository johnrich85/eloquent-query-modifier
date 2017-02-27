<?php namespace Johnrich85\EloquentQueryModifier;

/**
 * Class FilterQuery
 * @package Johnrich85\EloquentQueryModifier
 */
class FilterCountQuery extends FilterQuery
{
    /**
     * @var string
     */
    public $value = '1';

    /**
     * Instantiates FilterCountQuery, defaulting
     * operator to >=.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if(!isset($values['operator'])) {
            $values['operator'] = '>=';
        }

        parent::__construct($values);
    }
}