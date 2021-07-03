<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Digits;

class DigitsTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Digits();
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(false, $validate->isValid(0.9));
        $this->assertEquals(true, $validate->isValid('0'));
        $this->assertEquals(false, $validate->isValid('0.9'));
        $this->assertEquals(true, $validate->isValid(001));
        $this->assertEquals(true, $validate->isValid(0x2));
        $this->assertEquals(true, $validate->isValid(-5));
        $this->assertEquals(false, $validate->isValid('12e34'));
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
