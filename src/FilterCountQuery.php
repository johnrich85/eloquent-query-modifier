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
    public $operator = '>=';

    /**
     * @var string
     */
    public $value = '1';
}