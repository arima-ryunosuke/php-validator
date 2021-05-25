<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Step;

class StepTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        $this->assertException(new \InvalidArgumentException("positive number"), function () {
            new Step(0);
        });
        $this->assertException(new \InvalidArgumentException("positive number"), function () {
            new Step(-0.1);
        });
    }

    function test_valid()
    {
        $validate = new Step(5);
        $this->assertEquals($validate->isValid('invalid'), false);
        $this->assertEquals($validate->isValid(15), true);
        $this->assertEquals($validate->isValid(10), true);
        $this->assertEquals($validate->isValid(6), false);
        $this->assertEquals($validate->isValid(5), true);
        $this->assertEquals($validate->isValid(4), false);
        $this->assertEquals($validate->isValid(3), false);
        $this->assertEquals($validate->isValid(2), false);
        $this->assertEquals($validate->isValid(1), false);
        $this->assertEquals($validate->isValid(0.0), true);
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(-0), true);
        $this->assertEquals($validate->isValid(-0.0), true);
        $this->assertEquals($validate->isValid(-1), false);
        $this->assertEquals($validate->isValid(-2), false);
        $this->assertEquals($validate->isValid(-3), false);
        $this->assertEquals($validate->isValid(-4), false);
        $this->assertEquals($validate->isValid(-5), true);
        $this->assertEquals($validate->isValid(-6), false);
        $this->assertEquals($validate->isValid(-10), true);
        $this->assertEquals($validate->isValid(-15), true);

        $validate = new Step(0.5);
        $this->assertEquals($validate->isValid('invalid'), false);
        $this->assertEquals($validate->isValid(1.5), true);
        $this->assertEquals($validate->isValid(1.0), true);
        $this->assertEquals($validate->isValid(0.6), false);
        $this->assertEquals($validate->isValid(0.5), true);
        $this->assertEquals($validate->isValid(0.4), false);
        $this->assertEquals($validate->isValid(0.3), false);
        $this->assertEquals($validate->isValid(0.2), false);
        $this->assertEquals($validate->isValid(0.1), false);
        $this->assertEquals($validate->isValid(0.0), true);
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(-0), true);
        $this->assertEquals($validate->isValid(-0.0), true);
        $this->assertEquals($validate->isValid(-0.1), false);
        $this->assertEquals($validate->isValid(-0.2), false);
        $this->assertEquals($validate->isValid(-0.3), false);
        $this->assertEquals($validate->isValid(-0.4), false);
        $this->assertEquals($validate->isValid(-0.5), true);
        $this->assertEquals($validate->isValid(-0.6), false);
        $this->assertEquals($validate->isValid(-1.0), true);
        $this->assertEquals($validate->isValid(-1.5), true);
        $this->assertEquals($validate->isValid(5), true);
        $this->assertEquals($validate->isValid(4), true);
        $this->assertEquals($validate->isValid(3), true);
        $this->assertEquals($validate->isValid(2), true);
        $this->assertEquals($validate->isValid(1), true);
        $this->assertEquals($validate->isValid(-1), true);
        $this->assertEquals($validate->isValid(-2), true);
        $this->assertEquals($validate->isValid(-3), true);
        $this->assertEquals($validate->isValid(-4), true);
        $this->assertEquals($validate->isValid(-5), true);

        $validate = new Step(0.005);
        $this->assertEquals($validate->isValid('invalid'), false);
        $this->assertEquals($validate->isValid(0.015), true);
        $this->assertEquals($validate->isValid(0.010), true);
        $this->assertEquals($validate->isValid(0.006), false);
        $this->assertEquals($validate->isValid(0.005), true);
        $this->assertEquals($validate->isValid(0.004), false);
        $this->assertEquals($validate->isValid(0.003), false);
        $this->assertEquals($validate->isValid(0.002), false);
        $this->assertEquals($validate->isValid(0.001), false);
        $this->assertEquals($validate->isValid(0.0), true);
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(-0), true);
        $this->assertEquals($validate->isValid(-0.0), true);
        $this->assertEquals($validate->isValid(-0.001), false);
        $this->assertEquals($validate->isValid(-0.002), false);
        $this->assertEquals($validate->isValid(-0.003), false);
        $this->assertEquals($validate->isValid(-0.004), false);
        $this->assertEquals($validate->isValid(-0.005), true);
        $this->assertEquals($validate->isValid(-0.006), false);
        $this->assertEquals($validate->isValid(-0.010), true);
        $this->assertEquals($validate->isValid(-0.015), true);
        $this->assertEquals($validate->isValid(5), true);
        $this->assertEquals($validate->isValid(4), true);
        $this->assertEquals($validate->isValid(3), true);
        $this->assertEquals($validate->isValid(2), true);
        $this->assertEquals($validate->isValid(1), true);
        $this->assertEquals($validate->isValid(-1), true);
        $this->assertEquals($validate->isValid(-2), true);
        $this->assertEquals($validate->isValid(-3), true);
        $this->assertEquals($validate->isValid(-4), true);
        $this->assertEquals($validate->isValid(-5), true);

        $validate = new Step(0.4);
        $this->assertEquals($validate->isValid('invalid'), false);
        $this->assertEquals($validate->isValid(1.2), true);
        $this->assertEquals($validate->isValid(0.8), true);
        $this->assertEquals($validate->isValid(0.6), false);
        $this->assertEquals($validate->isValid(0.5), false);
        $this->assertEquals($validate->isValid(0.4), true);
        $this->assertEquals($validate->isValid(0.3), false);
        $this->assertEquals($validate->isValid(0.2), false);
        $this->assertEquals($validate->isValid(0.1), false);
        $this->assertEquals($validate->isValid(0.0), true);
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(-0), true);
        $this->assertEquals($validate->isValid(-0.0), true);
        $this->assertEquals($validate->isValid(-0.1), false);
        $this->assertEquals($validate->isValid(-0.2), false);
        $this->assertEquals($validate->isValid(-0.3), false);
        $this->assertEquals($validate->isValid(-0.4), true);
        $this->assertEquals($validate->isValid(-0.5), false);
        $this->assertEquals($validate->isValid(-0.6), false);
        $this->assertEquals($validate->isValid(-0.8), true);
        $this->assertEquals($validate->isValid(-1.2), true);
        $this->assertEquals($validate->isValid(5), false);
        $this->assertEquals($validate->isValid(4), true);
        $this->assertEquals($validate->isValid(3), false);
        $this->assertEquals($validate->isValid(2), true);
        $this->assertEquals($validate->isValid(1), false);
        $this->assertEquals($validate->isValid(-1), false);
        $this->assertEquals($validate->isValid(-2), true);
        $this->assertEquals($validate->isValid(-3), false);
        $this->assertEquals($validate->isValid(-4), true);
        $this->assertEquals($validate->isValid(-5), false);
    }

    function test_getRange()
    {
        $validate = new Step(0.05);
        $this->assertEquals(null, $validate->getMin());
        $this->assertEquals(null, $validate->getMax());
        $this->assertEquals('0.05', $validate->getStep());
    }

    function test_getImeMode()
    {
        $validate = new Step(1);
        $this->assertEquals(Step::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Step(1);
        $this->assertEquals('number', $validate->getType());
    }
}
