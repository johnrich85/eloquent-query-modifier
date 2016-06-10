<?php namespace Johnrich85\Tests;

use Johnrich85\EloquentQueryModifier\InputConfig;

class InputConfigTest extends \Johnrich85\Tests\BaseTest{

    public function setUp() {

    }

    public function getBasePath() {

    }

    public function testAddModifier()
    {
        $config = new InputConfig();

        $config->addModifier('test');

        $modifiers = $config->getModifiers();

        $this->assertEquals(true, in_array('test', $modifiers));
        $this->assertEquals(6, count($modifiers));
    }

    public function testDeleteModifier()
    {
        $config = new InputConfig();

        $config->removeModifier('\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier');

        $modifiers = $config->getModifiers();

        $this->assertEquals(false, in_array('\Johnrich85\EloquentQueryModifier\Modifiers\SearchModifier', $modifiers));
        $this->assertEquals(4, count($modifiers));
    }

    public function tearDown()
    {
    }
}
