<?php ;

use Johnrich85\EloquentQueryModifier\Modifiers\SortModifier;

class SortModifierTest extends Johnrich85\Tests\BaseTest {

    protected $testClass = '\Johnrich85\EloquentQueryModifier\Modifiers\SortModifier';

    public function testParseSortOrderDesc() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("-name");

        $method = $this->getMethod('parseSortOrder');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals("name", $modifier->getSortString());
        $this->assertEquals("DESC", $modifier->getOrder());
    }

    public function testParseSortOrderAsc() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("+name");

        $method = $this->getMethod('parseSortOrder');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals("name", $modifier->getSortString());
        $this->assertEquals("ASC", $modifier->getOrder());
    }

    public function testParseSortOrderDefault() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("name");

        $method = $this->getMethod('parseSortOrder');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals("name", $modifier->getSortString());
        $this->assertEquals("ASC", $modifier->getOrder());
    }

    public function testSymbolToOrderDesc() {
        $modifier = $this->_getInstance();

        $method = $this->getMethod('symbolToOrder');
        $result = $method->invokeArgs($modifier, array('-'));

        $this->assertEquals("DESC", $result);
    }

    public function testSymbolToOrderAsc() {
        $modifier = $this->_getInstance();

        $method = $this->getMethod('symbolToOrder');
        $result = $method->invokeArgs($modifier, array('+'));

        $this->assertEquals("ASC", $result);
    }

    public function testSymbolToOrderDefault() {
        $modifier = $this->_getInstance();

        $method = $this->getMethod('symbolToOrder');
        $result = $method->invokeArgs($modifier, array('a'));

        $this->assertEquals("ASC", $result);
    }

    public function testFetchValuesFromData() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getSort')
            ->will($this->returnValue('sort'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals("-name", $result);
    }

    public function testFetchValuesFromDataReturnsFalse() {
        $modifier = $this->_getInstance();

        $this->config->expects($this->any())
            ->method('getSort')
            ->will($this->returnValue('non-existent'));

        $method = $this->getMethod('fetchValuesFromData');
        $result = $method->invokeArgs($modifier, array());

        $this->assertEquals(false, $result);
    }

    public function testAddSortToQueryBuilderSingle() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("name");
        $modifier->setOrder("ASC");

        $data = array(
            'name' => 'name'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('orderBy'), $this->equalTo(array('name', 'ASC')))
            ->will($this->returnValue($this->builder));

        $method = $this->getMethod('addSortToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testAddSortToQueryBuilderMultiple() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("name, description");
        $modifier->setOrder("ASC");

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->at(0))
            ->method('__call')
            ->with($this->equalTo('orderBy'), $this->equalTo(array('name', 'ASC')))
            ->will($this->returnValue($this->builder));

        $this->builder->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('orderBy'), $this->equalTo(array('description', 'ASC')))
            ->will($this->returnValue($this->builder));

        $method = $this->getMethod('addSortToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testAddSortToQueryBuilderThrowsException() {
        $modifier = $this->_getInstance();
        $modifier->setSortString("-name, non-existent");
        $modifier->setOrder("ASC");

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->setExpectedException('Exception');

        $method = $this->getMethod('addSortToQueryBuilder');
        $method->invokeArgs($modifier, array());
    }

    public function testModify() {
        $modifier = $this->_getInstance();

        $data = array(
            'name' => 'name',
            'description' => 'description'
        );

        $this->config->expects($this->once())
            ->method('getSort')
            ->will($this->returnValue('sort'));

        $this->config->expects($this->any())
            ->method('getFilterableFields')
            ->will($this->returnValue($data));

        $this->builder->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('orderBy'), $this->equalTo(array('name', 'DESC')))
            ->will($this->returnValue($this->builder));

        $method = $this->getMethod('modify');
        $method->invokeArgs($modifier, array());
    }

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
