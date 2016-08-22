<?php namespace Johnrich85\Tests;

use Johnrich85\EloquentQueryModifier\InputConfig;
use Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Category;

class InputConfigTest extends \Johnrich85\Tests\BaseTest{

    public function testAddModifier()
    {
        $config = new InputConfig();

        $config->addModifier('test');

        $modifiers = $config->getModifiers();

        $this->assertEquals(true, in_array('test', $modifiers));
        $this->assertEquals(6, count($modifiers));
    }

    public function testDeleteModifier()
    {
        $config = new InputConfig();

        $config->removeModifier('\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier');

        $modifiers = $config->getModifiers();

        $this->assertEquals(false, in_array('\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier', $modifiers));
        $this->assertEquals(4, count($modifiers));
    }

    public function test_all_columns_returned()
    {
        $model = new Category();

        $config = new InputConfig();

        $config->setFilterableFields($model->query());

        $columns = $config->getFilterableFields();

        $this->assertEquals(4, count($columns));
        $this->assertContains('id', $columns);
        $this->assertContains('name', $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

}
