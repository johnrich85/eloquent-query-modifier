<?php ;

use \Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use \Johnrich85\EloquentQueryModifier\InputConfig;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class FilterModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_no_fields_returns_builder() {
        $model = new Models\Category();
        $query = $model->query();

        $data = [];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertEquals(null, $result->getQuery()->wheres);
    }

    public function test_empty_field_returns_builder() {
        $model = new Models\Category();
        $query = $model->query();

        $data = [
            'name' => '',
        ];

        $modifier = $this->getFilterModifierInstance($query, $data);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertEquals(null, $result->getQuery()->wheres);
    }

    public function test_where_in_shortcut() {
        $model = new Models\Category();
        $query = $model->query();

        $data = [
            'name' => [
                'test 1',
                'test 2'
            ]
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('In', $wheres[0]['type']);
        $this->assertEquals('test 1', $wheres[0]['values'][0]);
        $this->assertEquals('test 2', $wheres[0]['values'][1]);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }

    public function test_where_shortcut() {
        $model = new Models\Category();
        $query = $model->query();

        $data = [
            'name' => 'test 1'
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals('test 1', $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }


    public function test_object_filter() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 'Cat 1';

        $data = [
            'name' => $queryObject
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals('Cat 1', $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_object_filter_multi() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '>=';
        $queryObject->value = 'b';

        $queryObject2 = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject2->operator = '==';
        $queryObject2->value = 1;

        $data = [
            'name' => $queryObject,
            'id' => $queryObject2
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(2, count($wheres));

        $this->assertEquals('name', $wheres[1]['column']);
        $this->assertEquals('>=', $wheres[1]['operator']);
        $this->assertEquals('b', $wheres[1]['value']);
        $this->assertEquals('and', $wheres[1]['boolean']);

        $this->assertEquals('id', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals(1, $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }

    public function test_object_filter_multi_using_or() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '>=';
        $queryObject->value = 'b';

        $queryObject2 = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject2->operator = '==';
        $queryObject2->value = 1;

        $data = [
            'name' => $queryObject,
            'id' => $queryObject2
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null, 'or');

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(2, count($wheres));

        $this->assertEquals('name', $wheres[1]['column']);
        $this->assertEquals('>=', $wheres[1]['operator']);
        $this->assertEquals('b', $wheres[1]['value']);
        $this->assertEquals('or', $wheres[1]['boolean']);

        $this->assertEquals('id', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals(1, $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }

    public function test_json_filter() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 'Cat 1';

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals('Cat 1', $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_json_filter_with_0() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 0;

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals(0, $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }

    public function test_json_filter_with_false() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = false;

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals(0, $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);
    }

    public function test_json_operator_defaults() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = null;
        $queryObject->value = 'Cat 1';

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals('Cat 1', $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_json_without_value_does_nothing() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '=';
        $queryObject->value = null;

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(0, count($wheres));
    }

    public function test_invalid_json_filter() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 'Cat 1';

        $data = [
            'name' => json_encode($queryObject)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('=', $wheres[0]['operator']);
        $this->assertEquals('Cat 1', $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_json_filter_multi() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 'Cat 1';

        $queryObject2 = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject2->operator = '>=';
        $queryObject2->value = 1;

        $data = [
            'name' => json_encode($queryObject),
            'id' => json_encode($queryObject2)
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);
        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(2, count($wheres));

        $this->assertEquals('name', $wheres[1]['column']);
        $this->assertEquals('=', $wheres[1]['operator']);
        $this->assertEquals('Cat 1', $wheres[1]['value']);
        $this->assertEquals('and', $wheres[1]['boolean']);

        $this->assertEquals('id', $wheres[0]['column']);
        $this->assertEquals('>=', $wheres[0]['operator']);
        $this->assertEquals(1, $wheres[0]['value']);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_array_instead_of_object() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = '==';
        $queryObject->value = 'Cat 1';

        $data = [
            'name' => (array) $queryObject
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $this->setExpectedException(Exception::class);

        $modifier->modify($query);
    }

    protected function getFilterModifierInstance($query, $data, $config = null, $type = 'and')
    {
        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        if ($type == 'or') {
            $config->setFilterType('orWhere');
        }

        return new FilterModifier($data, $query, $config);
    }

    public function test_include() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = 'include';
        $queryObject->value = [
            'Cat 1',
            2,
            3
        ];

        $data = [
            'name' => $queryObject
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('In', $wheres[0]['type']);
        $this->assertEquals('Cat 1', $wheres[0]['values'][0]);
        $this->assertEquals(2, $wheres[0]['values'][1]);
        $this->assertEquals(3, $wheres[0]['values'][2]);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(1, count($query->get()));
    }

    public function test_exclude() {
        $this->populateDatabase();

        $model = new Models\Category();
        $query = $model->query();

        $queryObject = new \Johnrich85\EloquentQueryModifier\FilterQuery();
        $queryObject->operator = 'exclude';
        $queryObject->value = [
            'Cat 1',
            2,
            3
        ];

        $data = [
            'name' => $queryObject
        ];

        $modifier = $this->getFilterModifierInstance($query, $data, null);

        $result = $modifier->modify($query);

        $wheres = $result->getQuery()->wheres;

        $this->assertEquals(1, count($wheres));

        $this->assertEquals('name', $wheres[0]['column']);
        $this->assertEquals('NotIn', $wheres[0]['type']);
        $this->assertEquals('Cat 1', $wheres[0]['values'][0]);
        $this->assertEquals(2, $wheres[0]['values'][1]);
        $this->assertEquals(3, $wheres[0]['values'][2]);
        $this->assertEquals('and', $wheres[0]['boolean']);

        $this->assertEquals(0, count($query->get()));
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
