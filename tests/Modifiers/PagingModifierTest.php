<?php

use \Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use \Johnrich85\EloquentQueryModifier\InputConfig;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class PagingModifierTest extends Johnrich85\Tests\BaseTest {

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
            'page' => ''
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(null, $result->getQuery()->offset);
    }

    public function test_page_adds_offset() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'page' => 1
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(0, $result->getQuery()->offset);
    }

    public function test_page_zero_resolves_first_page() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'page' => 0
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(0, $result->getQuery()->offset);
    }

    public function test_per_page_default() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'page' => 3
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(20, $result->getQuery()->offset);
    }

    public function test_per_page_invalid() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'page' => 'sdf'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(0, $result->getQuery()->offset);
    }

    protected function getFilterModifierInstance($query, $data, $config = null)
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        return new \Johnrich85\EloquentQueryModifier\Modifiers\PagingModifier($data, $query, $config);
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
