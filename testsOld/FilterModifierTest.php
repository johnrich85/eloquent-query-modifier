<?php ;
use Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier;
use Illuminate\Support\Facades\DB;

class FilterModifierTest extends Johnrich85\Tests\BaseTest
{

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\FilterModifier';



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
