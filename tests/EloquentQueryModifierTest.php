<?php namespace Johnrich85\Tests;

use Laracasts\TestDummy\DbTestCase;

class EloquentQueryModifierTest extends DbTestCase
{

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('Johnrich85\EloquentQueryModifier\EloquentQueryModifier');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testModify()
    {
        //todo
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
