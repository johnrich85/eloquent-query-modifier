<?php namespace Johnrich85\Tests;

use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase{

    protected $config;
    protected $builder;
    protected $data;
    protected $testClass;

    /**
     *
     */
    public function setUp() {
        parent::setUp();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/Mock/migrations'),
        ]);
    }

    /**
     *
     */
    public function tearDown() {
        $this->artisan('migrate:reset', [
            '--database' => 'testbench'
        ]);

        parent::tearDown();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
