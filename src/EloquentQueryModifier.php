<?php namespace Johnrich85\EloquentQueryModifier;

use Johnrich85\EloquentQueryModifier\Factory\ModifierFactory;

class EloquentQueryModifier implements QueryModifier {

    /**
     * @var InputConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $input = array();

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * @var \Johnrich85\EloquentQueryModifier\Factory
     */
    protected $factory;

    /**
     * @param InputConfig $config
     */
    public function __construct(InputConfig $config, ModifierFactory $factory) {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function modify(\Illuminate\Database\Eloquent\Builder $builder, array $input) {
        $this->input = $input;
        $this->builder = $builder;

        $this->setConfigFilterableFields($builder);

        $this->callModifiers($this->config->getModifiers());

        return $this->builder;
    }

    /**
     * Gets a list of modifier names from
     * config, iterates over and instantiates
     * objects.
     *
     * @param array $modifiers
     */
    protected function callModifiers(array $modifiers) {

        $context = array(
            'input' => $this->input,
            'builder' => $this->builder,
            'config' => $this->config
        );

        foreach($modifiers as $modifier) {
            $instance = $this->factory->getInstance($modifier, $context);
            $this->builder = $instance->modify();
        }

    }

    /**
     * Retrieves the fields for a model &
     * assigns to the config.
     *
     * @param $builder
     */
    protected function setConfigFilterableFields(\Illuminate\Database\Eloquent\Builder $builder) {
        $this->config->setFilterableFields($builder);
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

}

interface QueryModifier {
    public function modify(\Illuminate\Database\Eloquent\Builder $builder, array $input);
}