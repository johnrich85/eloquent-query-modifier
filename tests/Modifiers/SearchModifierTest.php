<?php ;
use Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier;
use Illuminate\Support\Facades\DB;

class SearchModifierTest extends Johnrich85\Tests\BaseTest
{

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier';

    public function testModify()
    {
        $modifier = $this->getInstance();
    }

    public function testModifyWildcard()
    {
        $modifier = $this->getInstance();

        $this->config->expects($this->once())
            ->method('getSearch')
            ->will($this->returnValue('q'));

        $this->config->expects($this->once())
            ->method('getSearchMode')
            ->will($this->returnValue('wildcard'));

        $this->builder->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('search'), $this->equalTo(['test']))
            ->will($this->returnValue($this->builder));

        $modifier->modify();
    }

    public function testSearchUnsupported()
    {
        $modifier = $this->getInstance();

        $this->config->expects($this->once())
            ->method('getSearch')
            ->will($this->returnValue('q'));

        $this->config->expects($this->once())
            ->method('getSearchMode')
            ->will($this->returnValue('wildcard'));

        $this->builder->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('search'), $this->equalTo(['test']))
            ->will($this->throwException(new BadMethodCallException()));

        $this->setExpectedException('Exception');

        $modifier->modify();
    }

    public function testModifyLiteral()
    {
        $modifier = $this->getInstance();

        $this->config->expects($this->once())
            ->method('getSearch')
            ->will($this->returnValue('q'));

        $this->config->expects($this->once())
            ->method('getSearchMode')
            ->will($this->returnValue('literal'));

        $this->builder->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('search'), $this->equalTo(['test', false]))
            ->will($this->returnValue($this->builder));

        $modifier->modify();
    }

    public function testModifyThrowsException() {
        $modifier = $this->getInstance(false);

        $this->config->expects($this->any())
            ->method('getSearch')
            ->will($this->returnValue('q'));

        $this->setExpectedException('Exception');

        $modifier->modify();
    }

    protected function getInstance($data = true)
    {
        if ($data) {
            $this->data = array(
                'q' => 'test',
            );
        } else {
            $this->data = array(
                'q' => '',
            );
        }

        $this->config = $this->getMock('\Johnrich85\EloquentQueryModifier\InputConfig');

        $this->builder = $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();

        return new SearchModifier($this->data, $this->builder, $this->config);
    }
}
