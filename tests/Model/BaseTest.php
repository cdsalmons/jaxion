<?php
namespace Intraxia\Jaxion\Test\Model;

use Intraxia\Jaxion\Test\Stubs\MetaBase;
use Intraxia\Jaxion\Test\Stubs\TableBase;
use Mockery;

/**
 * @group model
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $mockPost = Mockery::mock('overload:WP_Post');
    }

    public function testShouldConstructToMetaWithNoTable()
    {
        $base = new MetaBase(array(
            'test' => 'value'
        ));

        $attributes = $base->getAttributes();
        $this->assertEquals('value', $attributes['test']);
        $this->assertEquals('value', $base->test);
    }

    public function testShouldAssignToMetaWithNoTable()
    {
        $base = new MetaBase();
        $base->test = 'value';

        $attributes = $base->getAttributes();
        $this->assertEquals('value', $attributes['test']);
        $this->assertEquals('value', $base->test);
    }

    public function testShouldConstructToTable()
    {
        $base = new TableBase(array(
            'test' => 'value'
        ));

        $attributes = $base->getAttributes();
        $this->assertEquals('value', $attributes['test']);
        $this->assertEquals('value', $base->test);
    }

    public function testShouldAssignToTable()
    {
        $base = new TableBase();
        $base->test = 'value';

        $attributes = $base->getAttributes();
        $this->assertEquals('value', $attributes['test']);
        $this->assertEquals('value', $base->test);
    }

    public function testShouldNotAssignPost()
    {
        $base = new TableBase();

        $this->assertFalse($base->getUnderlyingPost());
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}
