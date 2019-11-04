<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Date;

class DateTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Date('Y/m/d H:i:s');
        $this->assertEquals($validate->isValid('yyyy/09/30 12:34:56'), false);
        $this->assertEquals($validate->isValid('1985/13/30 12:34:56'), false);
        $this->assertEquals($validate->isValid('1985/09/32 12:34:56'), false);
        $this->assertEquals($validate->isValid('1985/09/30 25:34:56'), false);
        $this->assertEquals($validate->isValid('1985/09/30 12:61:56'), false);
        $this->assertEquals($validate->isValid('1985/09/30 12:34:65'), false);
        $this->assertEquals($validate->isValid('1985/09/30 12:34:56'), true);
        $this->assertEquals($validate->isValid('2040/09/30 12:34:56'), true);

        $validate = new Date('Y/m/d H:i');
        $this->assertEquals($validate->isValid('yyyy/09/30 12:34'), false);
        $this->assertEquals($validate->isValid('1985/13/30 12:34'), false);
        $this->assertEquals($validate->isValid('1985/09/32 12:34'), false);
        $this->assertEquals($validate->isValid('1985/09/30 25:34'), false);
        $this->assertEquals($validate->isValid('1985/09/30 12:61'), false);
        $this->assertEquals($validate->isValid('1985/09/30 12:34'), true);
        $this->assertEquals($validate->isValid('1985/09/30 12:34'), true);
        $this->assertEquals($validate->isValid('2040/09/30 12:34'), true);
    }

    function test_valid_time()
    {
        $validate = new Date('H:i:s');
        $this->assertEquals($validate->isValid('12:34'), false);
        $this->assertEquals($validate->isValid('12:34:60'), false);
        $this->assertEquals($validate->isValid('12:34:aa'), false);
        $this->assertEquals($validate->isValid('12:34:56'), true);
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
