<?php namespace Johnrich85\EloquentQueryModifier\Factory;

use Johnrich85\EloquentQueryModifier\Modifiers;
use Mockery\CountValidator\Exception;

class ModifierFactory {

    /**
     * @param $name
     * @param array[String] $context
     */
    public function getInstance($name, array $context) {
        $this->_validateContext($context);

        if(class_exists($name)) {
            return new $name($context['input'], $context['builder'], $context['config']);
        }
        else {
            throw new Exception('Attempt to instantiate non-existent class: ' . $name);
        }
    }

    /**
     * Checks context for reqired fields,
     * throws exception if not found.
     */
    protected function _validateContext($context) {
        if(!isset($context['input']) || empty($context['builder']) || empty($context['config'])) {
            throw new Exception("Context must contain an 'input', 'builder' and 'config' index.");
        }
    }
}