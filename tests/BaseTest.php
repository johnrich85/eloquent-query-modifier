<?php namespace Johnrich85\Tests;

use Laracasts\TestDummy\DbTestCase;

abstract class BaseTest extends DbTestCase{

    protected $config;
    protected $builder;
    protected $data;
    protected $testClass;

    public function setUp() {
        parent::setUp();
    }

    public function getBasePath() {
        return realpath(__DIR__.'/../../../../');
    }

    protected function getMethod($name) {
        $class = new \ReflectionClass($this->testClass);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
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
