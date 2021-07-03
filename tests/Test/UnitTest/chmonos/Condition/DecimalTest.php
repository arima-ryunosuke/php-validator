<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Decimal;

class DecimalTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Decimal(3, 3);
        $this->assertEquals(false, $validate->isValid('invalid'));
        $this->assertEquals(false, $validate->isValid(12345.12345));

        $validate = new Decimal(5, 3);
        $this->assertEquals(true, $validate->isValid(1.1));
        $this->assertEquals(true, $validate->isValid(12345.123));
        $this->assertEquals(false, $validate->isValid(123456.123));
        $this->assertEquals(false, $validate->isValid(12345.1234));
        $this->assertEquals(true, $validate->isValid(0.123));
        $this->assertEquals(false, $validate->isValid(0.1234));
        $this->assertEquals(true, $validate->isValid(12));
        $this->assertEquals(false, $validate->isValid(123456));

        $validate = new Decimal(5, 0);
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid(1));
        $this->assertEquals(true, $validate->isValid(12345));
        $this->assertEquals(false, $validate->isValid(12345.1));
        $this->assertEquals(false, $validate->isValid(12345.12));
        $this->assertEquals(false, $validate->isValid(12345.123));
        $this->assertEquals(false, $validate->isValid(12345.1234));
        $this->assertEquals(false, $validate->isValid(1.1));
        $this->assertEquals(false, $validate->isValid(1.12));
        $this->assertEquals(false, $validate->isValid(1.123));
        $this->assertEquals(false, $validate->isValid(1.1234));
    }

    function test_getRange()
    {
        $validate = new Decimal(3, 4);
        $this->assertEquals('-999.9999', $validate->getMin());
        $this->assertEquals('999.9999', $validate->getMax());
        $this->assertEquals('0.0001', $validate->getStep());

        $validate = new Decimal(3, 0);
        $this->assertEquals('-999', $validate->getMin());
        $this->assertEquals('999', $validate->getMax());
        $this->assertEquals('1', $validate->getStep());
    }

    function test_getImeMode()
    {
        $validate = new Decimal(3, 4);
        $this->assertEquals(Decimal::DISABLED, $validate->getImeMode());
    }

    function test_getMaxLength()
    {
        $validate = new Decimal(3, 4);
        $this->assertEquals(9, $validate->getMaxLength());
        $validate = new Decimal(5, 6);
        $this->assertEquals(13, $validate->getMaxLength());
    }

    function test_getType()
    {
        $validate = new Decimal(1, 2);
        $this->assertEquals('number', $validate->getType());
    }
}
