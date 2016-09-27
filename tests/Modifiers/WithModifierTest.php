<?php

use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class WithModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_no_with_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals([], $query->getEagerLoads());
    }

    public function test_empty_with_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'with' => []
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals([], $query->getEagerLoads());
    }

    public function test_valid_with_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'with' => [
                'themes' => []
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(true, array_key_exists('themes', $query->getEagerLoads()));
    }

    public function test_valid_json_with_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'with' => '{"themes":{}}'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals(true, array_key_exists('themes', $query->getEagerLoads()));
    }

    public function test_invalid_relation_does_not_add_eager_load() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'with' => ['xx' => []]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_valid_with_filters_adds_sub_query()
    {
        $query = $this->getMockQuery('Category');

        $sub_query = $this->getSubQueryMock();

        $query->shouldReceive('with')
            ->once()
            ->with(Mockery::on(function($args) use($sub_query) {
                $pass = true;

                if(!isset($args['themes']) || !is_callable($args['themes'])) {
                    $pass = false;
                }

                call_user_func($args['themes'], $sub_query);

                return $pass;
            }))
            ->passthru();

        $data = [
            'with' => [
                'themes' => [
                    'column' => 'name',
                    'operator' => '==',
                    'value' => 'Theme 1'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $modifier->modify($query);
    }

    public function test_valid_json_with_filter_adds_sub_query()
    {
        $query = $this->getMockQuery('Category');

        $sub_query = $this->getSubQueryMock();

        $query->shouldReceive('with')
            ->once()
            ->with(Mockery::on(function($args) use($sub_query) {
                $pass = true;

                if(!isset($args['themes']) || !is_callable($args['themes'])) {
                    $pass = false;
                }

                call_user_func($args['themes'], $sub_query);

                return $pass;
            }))
            ->passthru();

        $data = [
            'with' =>  '{"themes":{"column":"name", "operator":"==", "value":"Theme 1"}}'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);
    }

    public function test_valid_with_callback_filter()
    {
        $query = $this->getMockQuery('Category');

        $sub_query = $this->getSubQueryMock();

        $query->shouldReceive('with')
            ->once()
            ->with(Mockery::on(function($args) use($sub_query) {
                $pass = true;

                if(!isset($args['themes']) || !is_callable($args['themes'])) {
                    $pass = false;
                }

                call_user_func($args['themes'], $sub_query);

                return $pass;
            }))
            ->passthru();

        $data = [
            'with' => [
                'themes' => [
                    'callback' => function($q) {
                        $q->where('name', '=', 'Theme 1');
                    }
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);
    }

    public function test_integration_returns_expected()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $model2 = Models\Category::find(1);
        $query2 = $model2->query();

        $data = [
            'with' => [
                'themes' => [
                    'column' => 'name',
                    'operator' => '==',
                    'value' => 'Not here'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);

        $data['with']['themes']['value'] = 'Theme 1';
        $modifier2 = $this->getFilterModifierInstance($query2, $data);

        $result1 = $modifier->modify($query1);
        $result2 = $modifier2->modify($query2);

        $result1 = $result1->first();
        $result2 = $result2->first();

        $this->assertEquals(0, count($result1->themes));
        $this->assertEquals(1, count($result2->themes));
    }

    protected function getFilterModifierInstance($query, $data, $config = null)
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        return new \Johnrich85\EloquentQueryModifier\Modifiers\WithModifier($data, $query, $config);
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



    protected function getMockRelation()
    {
        $query = Mockery::mock('\Illuminate\Database\Eloquent\Relations\Relation')
            ->makePartial();

        return $query;
    }

}
