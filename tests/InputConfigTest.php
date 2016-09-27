<?php namespace Johnrich85\Tests;

use Johnrich85\EloquentQueryModifier\InputConfig;
use Johnrich85\EloquentQueryModifier\Modifiers\FieldSelectionModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\HasModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\PagingModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\SortModifier;
use Johnrich85\EloquentQueryModifier\Modifiers\WithModifier;
use Johnrich85\EloquentQueryModifier\Tests\Mock\Models\Category;

class InputConfigTest extends \Johnrich85\Tests\BaseTest{

    public function testAddModifier()
    {
        $config = new InputConfig();

        $config->addModifier('test');

        $modifiers = $config->getModifiers();

        $this->assertEquals(true, in_array('test', $modifiers));
        $this->assertEquals(8, count($modifiers));
    }

    public function testAllModifiersLoadedAsDefault()
    {
        $config = new InputConfig();

        $modifiers = $config->getModifiers();

        $this->assertEquals(7, count($modifiers));

        $this->assertContains(FieldSelectionModifier::class, $modifiers);
        $this->assertContains(FilterModifier::class, $modifiers);
        $this->assertContains(SortModifier::class, $modifiers);
        $this->assertContains(PagingModifier::class, $modifiers);
        $this->assertContains(SearchModifier::class, $modifiers);
        $this->assertContains(WithModifier::class, $modifiers);
        $this->assertContains(HasModifier::class, $modifiers);
    }

    public function testDeleteModifier()
    {
        $config = new InputConfig();

        $config->removeModifier(SearchModifier::class);

        $modifiers = $config->getModifiers();

        $this->assertEquals(false, in_array(SearchModifier::class, $modifiers));
        $this->assertEquals(6, count($modifiers));
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
