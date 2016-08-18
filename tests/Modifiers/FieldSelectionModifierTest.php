<?php ;

use Johnrich85\EloquentQueryModifier\Modifiers\FieldSelectionModifier;
use \Johnrich85\EloquentQueryModifier\InputConfig;
use \Johnrich85\EloquentQueryModifier\Tests\Mock\Models as Models;

class FieldSelectionModifierTest extends Johnrich85\Tests\BaseTest {

    public function test_does_add_required_fields_for_belongs_to_many_relation() {
        $this->populateDatabase();

        $model = new Models\Author();

        $query  = $model->query()
            ->with('books');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->books));
    }

    public function test_does_not_duplicate_keys() {
        $this->populateDatabase();

        $model = new Models\Author();

        $query  = $model->query()
            ->with('books');

        $modifier = $this->_getInstance($query, []);

        $result = $modifier->addRequiredFields(['id', 'name']);

        $this->assertEquals(2, count($result));
        $this->assertContains('id', $result);
        $this->assertContains('name', $result);
    }

    public function test_does_add_required_fields_for_belongs_to_relation() {
        $this->populateDatabase();

        $model = new Models\Author();

        $query  = $model->query()
            ->with('city');


        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('city_id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->city));
    }

    public function test_does_add_required_fields_for_has_many_relation() {
        $this->populateDatabase();

        $model = new Models\City();

        $query  = $model->query()
            ->with('authors');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->authors));
    }

    public function test_does_add_required_fields_for_has_many_through_relation() {
        $this->populateDatabase();

        $model = new Models\Country();

        $query  = $model->query()
            ->with('authors');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->authors));
    }

    public function test_does_add_required_fields_for_has_one_relation() {
        $this->populateDatabase();

        $model = new Models\Editor();

        $query  = $model->query()
            ->with('contact');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->contact));
    }

    public function test_does_add_required_fields_for_morph_many_relation() {
        $this->populateDatabase();

        $model = new Models\Editor();

        $query  = $model->query()
            ->with('books');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->books));
    }

    public function test_does_add_required_fields_for_morph_one_relation() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query  = $model->query()
            ->with('book');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertEquals(1, count($query->find(1)->book));
    }

    public function test_does_add_required_fields_for_morph_to_relation() {
        $this->populateDatabase();

        $model = new Models\Book();

        $query  = $model->query()
            ->with('bookable');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);

        $this->assertEquals(3, count($reqFields));
        $this->assertContains('bookable_id', $reqFields);
        $this->assertContains('bookable_type', $reqFields);
        $this->assertContains('name', $reqFields);
        $this->assertInstanceOf(Models\Editor::class, $query->find(2)->bookable);
    }

    public function test_does_add_required_fields_for_morph_to_many_relation() {
        $this->populateDatabase();

        $model = new Models\Category();

        $query  = $model->query()
            ->with('themes');

        $modifier = $this->_getInstance($query, ['fields' => 'name']);
        $modifier->modify($query);

        $reqFields = $modifier->addRequiredFields(['name']);


        $this->assertEquals(2, count($reqFields));
        $this->assertContains('id', $reqFields);
        $this->assertContains('name', $reqFields);

        $this->assertEquals(1, count($query->find(1)->themes));
    }

    public function test_with_no_fields_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, []);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertEquals(null, $result->getQuery()->columns);
    }

    public function test_empty_fields_returns_builder()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => '']);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertEquals(null, $result->getQuery()->columns);
    }

    public function test_supports_one_value()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => 'name']);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertContains('name', $result->getQuery()->columns);
    }

    public function test_supports_multi_value()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => 'name, id']);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertContains('name', $result->getQuery()->columns);
        $this->assertContains('id', $result->getQuery()->columns);
    }

    public function test_fields_are_trimmed()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => ' name , id ']);

        $result = $modifier->modify($query);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
        $this->assertContains('name', $result->getQuery()->columns);
        $this->assertContains('id', $result->getQuery()->columns);
    }

    public function test_invalid_format_throws_exception()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => 'id;name']);

        $this->setExpectedException(Exception::class);

        $result = $modifier->modify($query);
    }

    public function test_invalid_fields_throws_exception()
    {
        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => 'id, idontexist']);

        $this->setExpectedException(Exception::class);

        $result = $modifier->modify($query);
    }

    public function test_only_selected_fields_are_returned()
    {
        $this->populateDatabase();

        $model = new Models\Category();

        $query = $model->query();

        $modifier = $this->_getInstance($query, ['fields' => 'name']);

        $result = $modifier->modify($query);

        $model = $result->find(1);
        $atts = $model->getAttributes();

        $this->assertEquals(1, count($atts));
        $this->assertEquals(true, array_has($atts, 'name'));
    }


    public function populateDatabase()
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

    protected function _getInstance($query, $fields = [], $config = null) {
        $this->data = $fields;

        if(!$config) {
            $config = new \Johnrich85\EloquentQueryModifier\InputConfig();
            $config->setFilterableFields($query);
        }

        return new FieldSelectionModifier($this->data, $query, $config);
    }

    /**
     * @return mixed
     */
    protected function getEloquentBuilderMock()
    {
        return $this->getMockBuilder('\Illuminate\Database\Eloquent\Builder')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
