<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Range;

class RangeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new Range(1, null);

        $this->assertEquals($validate->isValid('0'), false);
        $this->assertEquals($validate->isValid('1'), true);
        $this->assertEquals($validate->isValid('999'), true);
        $this->assertEquals($validate->isValid(0), false);
        $this->assertEquals($validate->isValid(1), true);
        $this->assertEquals($validate->isValid(999), true);
    }

    function test_max()
    {
        $validate = new Range(null, 999);

        $this->assertEquals($validate->isValid('-1'), true);
        $this->assertEquals($validate->isValid('0'), true);
        $this->assertEquals($validate->isValid('1'), true);
        $this->assertEquals($validate->isValid('999'), true);
        $this->assertEquals($validate->isValid('1000'), false);
        $this->assertEquals($validate->isValid(-1), true);
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(1), true);
        $this->assertEquals($validate->isValid(999), true);
        $this->assertEquals($validate->isValid(1000), false);
    }

    function test_minmax()
    {
        $validate = new Range(1, 999);

        $this->assertEquals($validate->isValid('-1'), false);
        $this->assertEquals($validate->isValid('0'), false);
        $this->assertEquals($validate->isValid('1'), true);
        $this->assertEquals($validate->isValid('999'), true);
        $this->assertEquals($validate->isValid('1000'), false);
    }

    function test_getImeMode()
    {
        $validate = new Range(1, 999);
        $this->assertEquals(Range::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Range(1, 999);
        $this->assertEquals('range', $validate->getType());
    }

    function test_getRange()
    {
        $validate = new Range(-999, 999);

        $this->assertEquals('-999', $validate->getMin());
        $this->assertEquals('999', $validate->getMax());
        $this->assertEquals(null, $validate->getStep());
    }
}
