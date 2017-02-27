<?php namespace Johnrich85\EloquentQueryModifier\InputDecoders\Contract;

/**
 * Interface Decoder
 *
 * @package Johnrich85\EloquentQueryModifier\Decoders\Contract
 */
interface Decoder
{
    /**
     * Decodes and returns self
     * for chaining.
     *
     * @param $data
     * @return Decoder
     */
    public function decode($data);

    /**
     * Returns true if successful, false if error.
     *
     * @return bool
     */
    public function success();

    /**
     * Returns errors
     *
     * @return mixed
     */
    public function getErrors();

    /**
     * Returns decoded data.
     *
     * @return mixed
     */
    public function getData();
}