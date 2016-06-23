<?php ;
use Johnrich85\EloquentQueryModifier\Modifiers\FieldSelectionModifier;
use Illuminate\Support\Facades\DB;

class FieldSelectionModifierTest extends Johnrich85\Tests\BaseTest {

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\FieldSelectionModifier';

    public function testCheckForInvalidFields() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue(array(
                    'name' => 'name',
                    'description' => 'description'
                )
            ));

        $fields = array(
            'name',
            'description'
        );
        $method = $this->getMethod('getValidFields');
        $result = $method->invokeArgs($modifier, array($fields));

        $this->assertEquals($fields, $result);
    }

    public function testCheckForInvalidFieldsThrowsException() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue(array(
                    'name' => 'name',
                    'description' => 'description'
                )
            ));

        $fields = array(
            'name',
            'invalidField'
        );

        $this->setExpectedException('Exception');

        $method = $this->getMethod('getValidFields');
        $method->invokeArgs($modifier, array($fields));
    }

    public function testUnfoundFieldsWorkWhenUsingEagerLoading() {
        $modifier = $this->_getInstance();

        $this->builder->expects($this->any())
            ->method('getEagerLoads')
            ->will($this->returnValue([1]));

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue(array(
                    'name' => 'name',
                    'description' => 'description'
                )
            ));

        $fields = array(
            'name',
            'invalidField'
        );


        $method = $this->getMethod('getValidFields');
        $result = $method->invokeArgs($modifier, array($fields));

        $this->assertEquals(array('name'), $result);
    }

    public function testFetchValuesFromData() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue('fields'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals('name, description' ,$result);
    }

    public function testFetchValuesFromDataWithoutData() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue('non_existent_index'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals(false ,$result);
    }

    public function testModifyCallsSelect() {
        $modifier = $this->_getInstance();

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue('fields'));

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->atLeastOnce())
            ->method('__call')
            ->with($this->equalTo('select'),$this->anything())
            ->will($this->returnValue(true));

        $method = $this->getMethod('modify');
        $method->invokeArgs($modifier, array());
    }

    protected function _getInstance() {
        $this->data = array(
            'fields' => 'name, description'
        );
        $this->config = $this->getMock('\Johnrich85\EloquentQueryModifier\InputConfig');
        $this->builder = $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();


        return new FieldSelectionModifier($this->data, $this->builder, $this->config);
    }
}
