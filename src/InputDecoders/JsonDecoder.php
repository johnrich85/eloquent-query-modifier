<?php namespace Johnrich85\EloquentQueryModifier\InputDecoders;

use Johnrich85\EloquentQueryModifier\InputDecoders\Contract\Decoder;

/**
 * Class JsonDecoder
 *
 * Decodes json input.
 *
 * @package Johnrich85\EloquentQueryModifier\InputDecoders
 */
class JsonDecoder implements Decoder
{
    /**
     * Decoded data;
     * @var array
     */
    protected $data = [];

    /**
     * Errors.
     *
     * @var
     */
    protected $errors;

    /**
     * Status
     *
     * @var bool
     */
    protected $status;

    /**
     * @param $data
     * @return bool|array
     */
    public function decode($data)
    {
        if (!is_string($data)) {
            $this->status = false;
            $this->errors = JSON_ERROR_STATE_MISMATCH;

            return $this;
        }

        $json = json_decode($data, true);

        $isJson = is_array($json) && (json_last_error() == JSON_ERROR_NONE);

        if (!$isJson) {
            $this->status = false;
            $this->errors = json_last_error();
        } else {
            $this->status = true;
            $this->data = $json;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->status;
    }

    /**
     * @return mixed;
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}