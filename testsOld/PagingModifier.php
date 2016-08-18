<?php ;

use Johnrich85\EloquentQueryModifier\Modifiers\PagingModifier;

class PagingModifierTest extends Johnrich85\Tests\BaseTest {

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\PagingModifier';


    public function testAddPagingToQueryBuilder() {
        $modifier = $this->_getInstance();
        $modifier->setPageNum(2);

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('take'), $this->equalTo(array(10)))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('skip'), $this->equalTo(array(10)))
            ->will($this->returnValue($this->builder));


        $method = $this->getMethod('addPagingToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testAddPagingToQueryBuilderWithMinusValue() {
        $modifier = $this->_getInstance();
        $modifier->setPageNum(-1);

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('take'), $this->equalTo(array(10)))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('skip'), $this->equalTo(array(0)))
            ->will($this->returnValue($this->builder));


        $method = $this->getMethod('addPagingToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testAddPagingToQueryBuilderWithZero() {
        $modifier = $this->_getInstance();
        $modifier->setPageNum(0);

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('take'), $this->equalTo(array(10)))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('skip'), $this->equalTo(array(0)))
            ->will($this->returnValue($this->builder));


        $method = $this->getMethod('addPagingToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testFetchValuesFromData() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getPage')
            ->will($this->returnValue('page'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals("1", $result);
    }

    public function testFetchValuesFromDataReturnsFalse() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getPage')
            ->will($this->returnValue('non-existent'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals(false, $result);
    }

    public function testModifyReturnsBuilder() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getPage')
            ->will($this->returnValue('non-existent'));

        $method = $this->getMethod('modify');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals($this->builder, $result);
    }

    public function testModifyThrowsException() {
        $modifier = $this->_getInstance('');

        $this->config->expects($this->any())
            ->method('getPage')
            ->will($this->returnValue('page'));

        $this->setExpectedException('Exception');

        $method = $this->getMethod('modify');
        $method->invokeArgs($modifier, array());

    }

    public function testModify() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getPage')
            ->will($this->returnValue('page'));

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('take'), $this->equalTo(array(10)))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('skip'), $this->equalTo(array(0)))
            ->will($this->returnValue($this->builder));

        $method = $this->getMethod('modify');
        $method->invokeArgs($modifier, array());
    }

    protected function _getInstance($value = 1) {
        $this->data = array(
            'page' => $value,
            'description' => 'test'
        );

        $this->config = $this->getMock('\Johnrich85\EloquentQueryModifier\InputConfig');

        $this->builder = $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();


        return new PagingModifier($this->data, $this->builder, $this->config);
    }
}
