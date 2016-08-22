<?php

use \Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use \Johnrich85\EloquentQueryModifier\InputConfig;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class SortModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_no_page_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(null, $result->getQuery()->offset);
    }

    public function test_empty_page_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => ''
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(null, $result->getQuery()->offset);
    }

    public function test_sort_desc() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => '-name'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $orders = $result->getQuery()->orders;

        $this->assertEquals(1, count($orders));

        $this->assertEquals('name', $orders[0]['column']);
        $this->assertEquals('desc', $orders[0]['direction']);
    }

    public function test_sort_asc() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => '+name'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $orders = $result->getQuery()->orders;

        $this->assertEquals(1, count($orders));

        $this->assertEquals('name', $orders[0]['column']);
        $this->assertEquals('asc', $orders[0]['direction']);
    }

    public function test_sort_default() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => 'name'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $orders = $result->getQuery()->orders;

        $this->assertEquals(1, count($orders));

        $this->assertEquals('name', $orders[0]['column']);
        $this->assertEquals('asc', $orders[0]['direction']);
    }

    public function test_sort_excludes_eager_column() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query()
            ->with('book');

        $data = [
            'sort' => 'name, bookable_type'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $orders = $result->getQuery()->orders;

        $this->assertEquals(1, count($orders));

        $this->assertEquals('name', $orders[0]['column']);
        $this->assertEquals('asc', $orders[0]['direction']);
    }


    public function test_single() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => '-name'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $result = $query->get();

        $this->assertEquals(3, count($result));
        $this->assertEquals('Cat 1', $result[0]->name);
    }

    public function test_single_asc() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => '+name'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $result = $query->get();

        $this->assertEquals(3, count($result));
        $this->assertEquals('Another Cat', $result[0]->name);
    }

    public function test_multiple() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'sort' => '+name, id'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $orders = $result->getQuery()->orders;

        $result = $query->get();

        $this->assertEquals(2, count($orders));

        $this->assertEquals('Another Cat', $result[0]->name);
    }

    protected function getFilterModifierInstance($query, $data, $config = null)
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        return new \Johnrich85\EloquentQueryModifier\Modifiers\SortModifier($data, $query, $config);
    }

    /**
     * @return mixed
     */
    protected function getEloquentBuilderMock()
    {
        return $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
