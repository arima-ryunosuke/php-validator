<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Digits;

class DigitsTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Digits();
        $this->assertEquals($validate->isValid(0), true);
        $this->assertEquals($validate->isValid(0.9), false);
        $this->assertEquals($validate->isValid('0'), true);
        $this->assertEquals($validate->isValid('0.9'), false);
        $this->assertEquals($validate->isValid(001), true);
        $this->assertEquals($validate->isValid(0x2), true);
        $this->assertEquals($validate->isValid(-5), true);
        $this->assertEquals($validate->isValid('12e34'), false);
    }

    function test_getImeMode()
    {
        $validate = new Digits();
        $this->assertEquals(Digits::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Digits();
        $this->assertEquals('number', $validate->getType());
    }
}
