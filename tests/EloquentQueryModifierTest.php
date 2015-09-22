<?php ;
use Laracasts\TestDummy\Factory;
use Laracasts\TestDummy\DbTestCase;
use Johnrich85\EloquentQueryModifier\InputConfig;
use Johnrich85\EloquentQueryModifier\EloquentQueryModifier;
use Illuminate\Support\Facades\DB;

class StubModel extends \Illuminate\Database\Eloquent\Model {

}

class EloquentQueryModifierTest extends DbTestCase{

    public function setUp() {
        parent::setUp();
    }

    public function getBasePath() {
        return realpath(__DIR__.'/../../../../');
    }

    public function testSetConfigFilterableFields() {
//        $config = new InputConfig();
//        $eqm = new EloquentQueryModifier($config);
//
//        $this->_mockDB();
//        $model = new StubModel();
//
//        $setConfigFilterableFields = self::getMethod('setConfigFilterableFields');
//        $setConfigFilterableFields->invokeArgs($eqm, array($model->newQuery()));
//
//        $supportedProperties = $config->getFilterableFields();
//
//        $this->assertInternalType('array', $supportedProperties);
//        $this->assertArrayHasKey('id', $supportedProperties);
//        $this->assertArrayHasKey('name', $supportedProperties);
    }

    public function testAddWhereFilters() {

//        $this->_mockDB();
//        $model = new StubModel();
//
//        $config = new InputConfig();
//        $config->setFilterableFields($model->newQuery());
//
//        $eqm = new EloquentQueryModifier($config);
//        $eqm->setInput(array('name'=>'test'));
//        $eqm->setBuilder($model = new StubModel());
//
//        $addWhereFilters = self::getMethod('addWhereFilters');
//        $builder = $addWhereFilters->invokeArgs($eqm, array());
//        $bindings = $builder->getQuery()->getRawBindings();
//
//        $this->assertEquals($bindings['where'][0], 'test');
    }

    protected static function getMethod($name) {
        $class = new ReflectionClass('Johnrich85\EloquentQueryModifier\EloquentQueryModifier');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function _mockDB() {
        DB::shouldReceive('raw')->withAnyArgs()->once()->andReturn(new Illuminate\Database\Query\Expression('SHOW COLUMNS FROM StubModel'));
        DB::shouldReceive('select')->withAnyArgs()->once()->andReturn($this->_getFieldArray());
        DB::shouldReceive('rollback')->withAnyArgs();
    }

    protected function _getFieldArray() {
        $field1 = new \stdClass();
        $field1->Field = 'id';

        $field2 = new \stdClass();
        $field2->Field = 'name';

        return array($field1, $field2);
    }

    /**
     * Rollback transactions after each test.
     */
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
