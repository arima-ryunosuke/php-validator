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
        $this->assertEquals(false, $validate->isValid('invalid'));
        $this->assertEquals(true, $validate->isValid(15));
        $this->assertEquals(true, $validate->isValid(10));
        $this->assertEquals(false, $validate->isValid(6));
        $this->assertEquals(true, $validate->isValid(5));
        $this->assertEquals(false, $validate->isValid(4));
        $this->assertEquals(false, $validate->isValid(3));
        $this->assertEquals(false, $validate->isValid(2));
        $this->assertEquals(false, $validate->isValid(1));
        $this->assertEquals(true, $validate->isValid(0.0));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid(-0));
        $this->assertEquals(true, $validate->isValid(-0.0));
        $this->assertEquals(false, $validate->isValid(-1));
        $this->assertEquals(false, $validate->isValid(-2));
        $this->assertEquals(false, $validate->isValid(-3));
        $this->assertEquals(false, $validate->isValid(-4));
        $this->assertEquals(true, $validate->isValid(-5));
        $this->assertEquals(false, $validate->isValid(-6));
        $this->assertEquals(true, $validate->isValid(-10));
        $this->assertEquals(true, $validate->isValid(-15));

        $validate = new Step(0.5);
        $this->assertEquals(false, $validate->isValid('invalid'));
        $this->assertEquals(true, $validate->isValid(1.5));
        $this->assertEquals(true, $validate->isValid(1.0));
        $this->assertEquals(false, $validate->isValid(0.6));
        $this->assertEquals(true, $validate->isValid(0.5));
        $this->assertEquals(false, $validate->isValid(0.4));
        $this->assertEquals(false, $validate->isValid(0.3));
        $this->assertEquals(false, $validate->isValid(0.2));
        $this->assertEquals(false, $validate->isValid(0.1));
        $this->assertEquals(true, $validate->isValid(0.0));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid(-0));
        $this->assertEquals(true, $validate->isValid(-0.0));
        $this->assertEquals(false, $validate->isValid(-0.1));
        $this->assertEquals(false, $validate->isValid(-0.2));
        $this->assertEquals(false, $validate->isValid(-0.3));
        $this->assertEquals(false, $validate->isValid(-0.4));
        $this->assertEquals(true, $validate->isValid(-0.5));
        $this->assertEquals(false, $validate->isValid(-0.6));
        $this->assertEquals(true, $validate->isValid(-1.0));
        $this->assertEquals(true, $validate->isValid(-1.5));
        $this->assertEquals(true, $validate->isValid(5));
        $this->assertEquals(true, $validate->isValid(4));
        $this->assertEquals(true, $validate->isValid(3));
        $this->assertEquals(true, $validate->isValid(2));
        $this->assertEquals(true, $validate->isValid(1));
        $this->assertEquals(true, $validate->isValid(-1));
        $this->assertEquals(true, $validate->isValid(-2));
        $this->assertEquals(true, $validate->isValid(-3));
        $this->assertEquals(true, $validate->isValid(-4));
        $this->assertEquals(true, $validate->isValid(-5));

        $validate = new Step(0.005);
        $this->assertEquals(false, $validate->isValid('invalid'));
        $this->assertEquals(true, $validate->isValid(0.015));
        $this->assertEquals(true, $validate->isValid(0.010));
        $this->assertEquals(false, $validate->isValid(0.006));
        $this->assertEquals(true, $validate->isValid(0.005));
        $this->assertEquals(false, $validate->isValid(0.004));
        $this->assertEquals(false, $validate->isValid(0.003));
        $this->assertEquals(false, $validate->isValid(0.002));
        $this->assertEquals(false, $validate->isValid(0.001));
        $this->assertEquals(true, $validate->isValid(0.0));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid(-0));
        $this->assertEquals(true, $validate->isValid(-0.0));
        $this->assertEquals(false, $validate->isValid(-0.001));
        $this->assertEquals(false, $validate->isValid(-0.002));
        $this->assertEquals(false, $validate->isValid(-0.003));
        $this->assertEquals(false, $validate->isValid(-0.004));
        $this->assertEquals(true, $validate->isValid(-0.005));
        $this->assertEquals(false, $validate->isValid(-0.006));
        $this->assertEquals(true, $validate->isValid(-0.010));
        $this->assertEquals(true, $validate->isValid(-0.015));
        $this->assertEquals(true, $validate->isValid(5));
        $this->assertEquals(true, $validate->isValid(4));
        $this->assertEquals(true, $validate->isValid(3));
        $this->assertEquals(true, $validate->isValid(2));
        $this->assertEquals(true, $validate->isValid(1));
        $this->assertEquals(true, $validate->isValid(-1));
        $this->assertEquals(true, $validate->isValid(-2));
        $this->assertEquals(true, $validate->isValid(-3));
        $this->assertEquals(true, $validate->isValid(-4));
        $this->assertEquals(true, $validate->isValid(-5));

        $validate = new Step(0.4);
        $this->assertEquals(false, $validate->isValid('invalid'));
        $this->assertEquals(true, $validate->isValid(1.2));
        $this->assertEquals(true, $validate->isValid(0.8));
        $this->assertEquals(false, $validate->isValid(0.6));
        $this->assertEquals(false, $validate->isValid(0.5));
        $this->assertEquals(true, $validate->isValid(0.4));
        $this->assertEquals(false, $validate->isValid(0.3));
        $this->assertEquals(false, $validate->isValid(0.2));
        $this->assertEquals(false, $validate->isValid(0.1));
        $this->assertEquals(true, $validate->isValid(0.0));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid(-0));
        $this->assertEquals(true, $validate->isValid(-0.0));
        $this->assertEquals(false, $validate->isValid(-0.1));
        $this->assertEquals(false, $validate->isValid(-0.2));
        $this->assertEquals(false, $validate->isValid(-0.3));
        $this->assertEquals(true, $validate->isValid(-0.4));
        $this->assertEquals(false, $validate->isValid(-0.5));
        $this->assertEquals(false, $validate->isValid(-0.6));
        $this->assertEquals(true, $validate->isValid(-0.8));
        $this->assertEquals(true, $validate->isValid(-1.2));
        $this->assertEquals(false, $validate->isValid(5));
        $this->assertEquals(true, $validate->isValid(4));
        $this->assertEquals(false, $validate->isValid(3));
        $this->assertEquals(true, $validate->isValid(2));
        $this->assertEquals(false, $validate->isValid(1));
        $this->assertEquals(false, $validate->isValid(-1));
        $this->assertEquals(true, $validate->isValid(-2));
        $this->assertEquals(false, $validate->isValid(-3));
        $this->assertEquals(true, $validate->isValid(-4));
        $this->assertEquals(false, $validate->isValid(-5));
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
