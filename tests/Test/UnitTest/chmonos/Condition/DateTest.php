<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Date;

class DateTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->isValid('yyyy/09/30 12:34:56')->isFalse();
        that($validate)->isValid('1985/13/30 12:34:56')->isFalse();
        that($validate)->isValid('1985/09/32 12:34:56')->isFalse();
        that($validate)->isValid('1985/09/30 25:34:56')->isFalse();
        that($validate)->isValid('1985/09/30 12:61:56')->isFalse();
        that($validate)->isValid('1985/09/30 12:34:65')->isFalse();
        that($validate)->isValid('1985/09/30 12:34:56')->isTrue();
        that($validate)->isValid('2040/09/30 12:34:56')->isTrue();

        $validate = new Date('Y/m/d H:i');
        that($validate)->isValid('yyyy/09/30 12:34')->isFalse();
        that($validate)->isValid('1985/13/30 12:34')->isFalse();
        that($validate)->isValid('1985/09/32 12:34')->isFalse();
        that($validate)->isValid('1985/09/30 25:34')->isFalse();
        that($validate)->isValid('1985/09/30 12:61')->isFalse();
        that($validate)->isValid('1985/09/30 12:34')->isTrue();
        that($validate)->isValid('1985/09/30 12:34')->isTrue();
        that($validate)->isValid('2040/09/30 12:34')->isTrue();
    }

    function test_valid_time()
    {
        $validate = new Date('H:i:s');
        that($validate)->isValid('12:34')->isFalse();
        that($validate)->isValid('12:34:60')->isFalse();
        that($validate)->isValid('12:34:aa')->isFalse();
        that($validate)->isValid('12:34:56')->isTrue();
    }

    function test_getImeMode()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getImeMode()->is(Date::DISABLED);
    }

    function test_getMaxLength()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getMaxLength()->is(19);
        $validate = new Date('H:i:s');
        that($validate)->getMaxLength()->is(8);
    }

    function test_getType()
    {
        $validate = new Date('H:i:s');
        that($validate)->getType()->is("text");
    }

    function test_getValue()
    {
        $validate = new Date('Y-m-d\TH:i:s');
        that($validate)->getValue(new \DateTime('2009-02-14 08:31:30'))->is("2009-02-14T08:31:30");
        that($validate)->getValue("1234567890")->is("2009-02-14T08:31:30");
        that($validate)->getValue('2009-02-14 08:31:30')->is("2009-02-14T08:31:30");
        that($validate)->getValue("")->is("");
        that($validate)->getValue("invalid")->is("invalid");
    }
}
