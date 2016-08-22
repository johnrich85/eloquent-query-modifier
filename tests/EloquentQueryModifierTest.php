<?php namespace Johnrich85\Tests;

use Illuminate\Support\Facades\Input;
use Johnrich85\EloquentQueryModifier\EloquentQueryModifier;
use Johnrich85\EloquentQueryModifier\Factory\ModifierFactory;
use Johnrich85\EloquentQueryModifier\FilterQuery;
use Johnrich85\EloquentQueryModifier\InputConfig;
use Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Category;

class EloquentQueryModifierTest extends \Johnrich85\Tests\BaseTest{

    public function test_no_parameters_returns_expected()
    {
        $this->populateDatabase();
        $modifier = $this->getEqmInstance();
        $builder = $this->getCatQueryBuilder();

        $modifier->modify($builder,[]);

        $results = $builder->get();

        $this->assertEquals(3, count($results));
    }

    public function test_with_multiple_default_modifiers_returns_expected()
    {
        $this->populateDatabase();

        $modifier = $this->getEqmInstance();
        $builder = $this->getCatQueryBuilder();

        $query = new FilterQuery();
        $query->operator = '!=';
        $query->value = 'Cat 1';

        $modifier->modify($builder,[
            'sort' => 'name',
            'name' => $query,
            'fields' => 'name'
        ]);

        $results = $builder->get();

        $this->assertEquals(2, count($results));
        $this->assertEquals('Another Cat', $results[0]->name);
        $this->assertEquals(1, count($results[0]));
        $this->assertEquals(true, array_key_exists('name', (array) $results[0]->getAttributes()));
    }

    protected function getEqmInstance()
    {
        $config = new InputConfig();
        $factory = new ModifierFactory();

        $modifier = new EloquentQueryModifier($config, $factory);

        return $modifier;
    }

    protected function getCatQueryBuilder()
    {
        $cat = new Category();

        $builder = $cat->newQuery();

        return $builder;
    }
}
