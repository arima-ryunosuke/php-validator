<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Date;

class DateTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Date('Y/m/d H:i:s');
        $this->assertEquals(false, $validate->isValid('yyyy/09/30 12:34:56'));
        $this->assertEquals(false, $validate->isValid('1985/13/30 12:34:56'));
        $this->assertEquals(false, $validate->isValid('1985/09/32 12:34:56'));
        $this->assertEquals(false, $validate->isValid('1985/09/30 25:34:56'));
        $this->assertEquals(false, $validate->isValid('1985/09/30 12:61:56'));
        $this->assertEquals(false, $validate->isValid('1985/09/30 12:34:65'));
        $this->assertEquals(true, $validate->isValid('1985/09/30 12:34:56'));
        $this->assertEquals(true, $validate->isValid('2040/09/30 12:34:56'));

        $validate = new Date('Y/m/d H:i');
        $this->assertEquals(false, $validate->isValid('yyyy/09/30 12:34'));
        $this->assertEquals(false, $validate->isValid('1985/13/30 12:34'));
        $this->assertEquals(false, $validate->isValid('1985/09/32 12:34'));
        $this->assertEquals(false, $validate->isValid('1985/09/30 25:34'));
        $this->assertEquals(false, $validate->isValid('1985/09/30 12:61'));
        $this->assertEquals(true, $validate->isValid('1985/09/30 12:34'));
        $this->assertEquals(true, $validate->isValid('1985/09/30 12:34'));
        $this->assertEquals(true, $validate->isValid('2040/09/30 12:34'));
    }

    function test_valid_time()
    {
        $validate = new Date('H:i:s');
        $this->assertEquals(false, $validate->isValid('12:34'));
        $this->assertEquals(false, $validate->isValid('12:34:60'));
        $this->assertEquals(false, $validate->isValid('12:34:aa'));
        $this->assertEquals(true, $validate->isValid('12:34:56'));
    }

    function test_getImeMode()
    {
        $validate = new Date('Y/m/d H:i:s');
        $this->assertEquals(Date::DISABLED, $validate->getImeMode());
    }

    function test_getMaxLength()
    {
        $validate = new Date('Y/m/d H:i:s');
        $this->assertEquals(19, $validate->getMaxLength());
        $validate = new Date('H:i:s');
        $this->assertEquals(8, $validate->getMaxLength());
    }

    function test_getType()
    {
        $validate = new Date('H:i:s');
        $this->assertEquals('text', $validate->getType());
    }
}
