<?php ;

use Johnrich85\EloquentQueryModifier\Modifiers\SortModifier;

class SortModifierTest extends Johnrich85\Tests\BaseTest {

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\SortModifier';


    public function testModifyReturnsBuilder() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getSort')
            ->will($this->returnValue('non-existent'));

        $method = $this->getMethod('modify');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals($this->builder, $result);
    }

    public function testModifyThrowsException() {
        $modifier = $this->_getInstance('');

        $this->config->expects($this->any())
            ->method('getSort')
            ->will($this->returnValue('sort'));

        $this->setExpectedException('Exception');

        $method = $this->getMethod('modify');
        $method->invokeArgs($modifier, array());

    }

    protected function _getInstance($value = "-name") {
        $this->data = array(
            'sort' => $value,
        );

        $this->config = $this->getMock('\Johnrich85\EloquentQueryModifier\InputConfig');

        $this->builder = $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();


        return new SortModifier($this->data, $this->builder, $this->config);
    }
}
