<?php namespace Johnrich85\Tests;

use Orchestra\Testbench\TestCase;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;


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

    protected function populateDatabase()
    {
        $country = new Models\Country([
            'name' => 'United Kingdom'
        ]);
        $country->save();

        $city = new Models\City([
            'name' => 'Newcastle',
            'country_id' => 1
        ]);
        $city->save();

        $book = new Models\Book([
            'name' => 'Book 1'
        ]);

        $this->createAuthor($book, $city);

        $this->createEditor();

        $this->createCategory();
    }

    /**
     * @param $book
     * @param $city
     */
    protected function createAuthor($book, $city)
    {
        $author = new Models\Author([
            'name' => 'Author 1'
        ]);

        $author->save();

        $author->books()->save($book);

        $author->city()->associate($city);

        $author->save();
    }

    /**
     * @return Models\Editor
     */
    protected function createEditor()
    {
        $editor = new Models\Editor([
            'name' => 'Editor 1'
        ]);

        $editor->save();

        $contact = new Models\Contact([
            'phone' => 123
        ]);

        $editor->contact()->save($contact);

        $book = new Models\Book([
            'name' => 'Book 2'
        ]);

        $book->save();

        $editor->books()->save($book);

        return $editor;
    }

    /**
     * @return Models\Category
     */
    protected function createCategory()
    {
        $cat = new Models\Category([
            'name' => 'Cat 1'
        ]);

        $cat->save();

        $cat2 = new Models\Category([
            'name' => 'Another Cat'
        ]);
        $cat2->save();

        $book = new Models\Book([
            'name' => 'Book 3'
        ]);

        $book->save();

        $cat->book()->save($book);

        $theme = new Models\Theme([
            'name' => 'Theme 1'
        ]);

        $theme->save();

        $cat->themes()->save($theme);

        return $cat;
    }

}
