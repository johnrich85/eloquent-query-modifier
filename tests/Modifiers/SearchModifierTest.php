<?php

use \Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use \Johnrich85\EloquentQueryModifier\InputConfig;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class SearchModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_no_search_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [];

        $modifier = $this->getSearchModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_empty_search_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'q' => ''
        ];

        $modifier = $this->getSearchModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_search_on_model_without_eloquence_trait() {
        $this->populateDatabase();

        $model = new Models\Author();

        $query = $model->query();

        $data = [
            'q' => 'Another Cat'
        ];

        $modifier = $this->getSearchModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_search_wildcard() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'q' => 'Another Cat'
        ];

        $modifier = $this->getSearchModifierInstance($query, $data);

        $modifier->modify($query);

        $this->assertEquals(2, count($query->get()));
    }

    public function test_search_literal() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'q' => '"Another Cat"'
        ];

        $modifier = $this->getSearchModifierInstance($query, $data, null, 'not');

        $modifier->modify($query);

        $this->assertEquals(1, count($query->get()));
    }

    protected function getSearchModifierInstance($query, $data, $config = null, $searchMode = 'column_limited')
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        $config->setSearchMode($searchMode);

        return new \Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier($data, $query, $config);
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
