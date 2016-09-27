<?php

use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class HasModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_without_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals([], $query->getEagerLoads());
    }

    public function test_empty_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => []
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $this->assertEquals([], $query->getEagerLoads());
    }

    public function test_valid_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => []
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('Exists', $wheres[0]['type']);
        $this->assertEquals('themes', $wheres[0]['query']->from);
    }

    public function test_valid_string_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => 'themes'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('Exists', $wheres[0]['type']);
        $this->assertEquals('themes', $wheres[0]['query']->from);
    }

    public function test_valid_string__multi_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => 'themes, book'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('Exists', $wheres[0]['type']);
        $this->assertEquals('themes', $wheres[0]['query']->from);
    }


    public function test_no_column_in_query_throws_exception()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'value' => 'No column provided.'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_empty_column_in_query_throws_exception()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'column' => '',
                    'value' => 'No column provided.'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_missing_value_in_query_throws_exception()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'column' => 'name'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_with_count_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'count' => [
                        'value' => 2
                    ]
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;


        $this->assertEquals('Basic', $wheres[0]['type']);

        $expectedQuery = '(select count(*) from "themes" inner join "themables" on "themes"."id" = "themables"."theme_id" where "themables"."themable_id" = "categories"."id" and "themables"."themable_type" = ?)';
        $this->assertEquals($expectedQuery, $wheres[0]['column']->__toString());

        $this->assertEquals(2, $wheres[0]['value']->__toString());
    }

    public function test_count_default_operator()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'count' => [
                        'value' => 2
                    ]
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('>=', $wheres[0]['operator']);
    }

    public function test_count_when_using_callback()
    {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => [
                'themes' => [
                    'count' => [
                        'value' => 2
                    ],
                    'callback' => function($q) {
                        $q->where('name', '=', 'Theme 1');
                    }
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('Basic', $wheres[0]['type']);

        $expectedQuery = '(select count(*) from "themes" inner join "themables" on "themes"."id" = "themables"."theme_id" where "themables"."themable_id" = "categories"."id" and "themables"."themable_type" = ? and "name" = ?)';
        $this->assertEquals($expectedQuery, $wheres[0]['column']->__toString());

        $this->assertEquals(2, $wheres[0]['value']->__toString());
    }

    public function test_valid_json_with_returns_builder() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => '{"themes":{}}'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals('Exists', $wheres[0]['type']);
        $this->assertEquals('themes', $wheres[0]['query']->from);
    }

    public function test_invalid_relation_throws_exception() {
        $model = new Models\Category();

        $query = $model->query();

        $data = [
            'has' => ['xx' => []]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    public function test_integration_json_returns_expected()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $data = [
            'has' => '{"themes":{"column":"name", "operator":"==", "value":"Theme 1"}}'
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);


        $result1 = $modifier->modify($query1);


        $result1 = $result1->first();

        $this->assertEquals(1, count($result1));
    }

    public function test_integration_callback_returns_expected()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $data = [
            'has' => [
                'themes' => [
                    'callback' => function($q) {
                        $q->where('name', '=', 'Theme 1');
                    }
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);

        $result1 = $modifier->modify($query1);

        $result1 = $result1->first();

        $this->assertEquals(1, count($result1));
    }

    public function test_integration_callback_with_count_returns_expected()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $model2 = Models\Category::find(1);
        $query2 = $model2->query();

        $data = [
            'has' => [
                'themes' => [
                    'callback' => function($q) {
                        $q->where('name', '=', 'Theme 1');
                    },
                    'count' => [
                        'value' => 2
                    ]
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);
        $result1 = $modifier->modify($query1);
        $result1 = $result1->first();

        $data['has']['themes']['count']['value'] = 1;
        $modifier2 = $this->getFilterModifierInstance($query2, $data);
        $result2 = $modifier2->modify($query2);
        $result2 = $result2->first();

        $this->assertEquals(0, count($result1));
        $this->assertEquals(1, count($result2));
    }

    public function test_integration_returns_expected()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $model2 = Models\Category::find(1);
        $query2 = $model2->query();

        $data = [
            'has' => [
                'themes' => [
                    'column' => 'name',
                    'operator' => '==',
                    'value' => 'Not here'
                ]
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);

        $data['has']['themes']['value'] = 'Theme 1';
        $modifier2 = $this->getFilterModifierInstance($query2, $data);

        $result1 = $modifier->modify($query1);
        $result2 = $modifier2->modify($query2);

        $result1 = $result1->first();
        $result2 = $result2->first();

        $this->assertEquals(0, count($result1));
        $this->assertEquals(1, count($result2));
    }

    public function test_integration_returns_expected_with_multiple()
    {
        $this->populateDatabase();

        $model1 = Models\Category::find(1);
        $query1 = $model1->query();

        $model2 = Models\Category::find(1);
        $query2 = $model2->query();

        $data = [
            'has' => [
                'themes' => [
                    'column' => 'name',
                    'operator' => '==',
                    'value' => 'Not here'
                ],
                'book' => []
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query1, $data);

        $data['has']['themes']['value'] = 'Theme 1';
        $modifier2 = $this->getFilterModifierInstance($query2, $data);

        $result1 = $modifier->modify($query1);
        $result2 = $modifier2->modify($query2);

        $result1 = $result1->first();
        $result2 = $result2->first();

        $this->assertEquals(0, count($result1));
        $this->assertEquals(1, count($result2));
    }

    /**
     * @param $query
     * @param $data
     * @param null $config
     * @return \Johnrich85\EloquentQueryModifier\Modifiers\HasModifier
     */
    protected function getFilterModifierInstance($query, $data, $config = null)
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        return new \Johnrich85\EloquentQueryModifier\Modifiers\HasModifier($data, $query, $config);
    }
}
