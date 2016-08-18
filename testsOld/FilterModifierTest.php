<?php ;
use Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use Illuminate\Support\Facades\DB;

class FilterModifierTest extends Johnrich85\Tests\BaseTest
{

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier';

    public function testGetFilterableFields()
    {
        $modifier = $this->_getInstance();

        $fields = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($fields));

        $method = $this->getMethod('getFilterableFields');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals($result, $fields);
    }

    public function testGetFilterableFieldsReturnsFalse()
    {
        $modifier = $this->_getInstance();

        $fields = array();

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($fields));

        $method = $this->getMethod('getFilterableFields');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals(false, $result);
    }

    public function testModify()
    {
        $modifier = $this->_getInstance();

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->at(0))
            ->method('where')
            ->with($this->equalTo('name'), $this->equalTo('test'))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('where')
            ->with($this->equalTo('description'), $this->equalTo('test'))
            ->will($this->returnValue($this->builder));

        $modifier->modify();
    }

    public function testModifyWithValidJson()
    {
        //todo
    }

    public function testModifyWithInvalidJsonThrowsException()
    {
        //todo
    }

    public function testJsonSupportsFalseValue()
    {
        //todo
    }

    public function testJsonSupportsZeroValue()
    {
        //todo
    }

    public function testModifyUsingOr()
    {
        $modifier = $this->_getInstance('or');

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->once())
            ->method('where')
            ->with($this->equalTo('name'), $this->equalTo('test'))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->once())
            ->method('orWhere')
            ->with($this->equalTo('description'), $this->equalTo('test'))
            ->will($this->returnValue($this->builder));

        $modifier->modify();
    }

    public function testModifyReturnsBuilder()
    {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue(false));

        $result = $modifier->modify();

        $this->assertEquals($this->builder, $result);
    }

    public function testModifyThrowsException()
    {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue(''));

        $this->setExpectedException('Exception');

        $modifier->modify();
    }

    protected function _getInstance($type = 'and')
    {
        $this->data = array(
            'name' => 'test',
            'description' => 'test'
        );

        $this->config = $this->getMock('\Johnrich85\EloquentQueryModifier\InputConfig',
            ['setFilterableFields', 'getFilterableFields']);


        if ($type == 'or') {
            $this->config->setFilterType('orWhere');
        }

        $this->builder = $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();


        return new FilterModifier($this->data, $this->builder, $this->config);
    }
}
