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
        that($validate)->isValid('12:34')->isTrue();
        that($validate)->isValid('12:34:00')->isTrue();
        that($validate)->isValid('12:34:60')->isFalse();
        that($validate)->isValid('12:34:aa')->isFalse();
        that($validate)->isValid('12:34:56')->isTrue();
        $validate = new Date('H:i');
        that($validate)->isValid('12:34')->isTrue();
        that($validate)->isValid('24:00:00')->isFalse();
        that($validate)->isValid('12:34:00')->isFalse();
        that($validate)->isValid('12:34:56')->isFalse();
    }

    function test_getMin()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getMin()->isSame(null);
        $validate = new Date('Y/m/d H:i');
        that($validate)->getMin()->isSame(null);
        $validate = new Date('Y/m/d');
        that($validate)->getMin()->isSame(null);
        $validate = new Date('Y/m');
        that($validate)->getMin()->isSame(null);
        $validate = new Date('Y');
        that($validate)->getMin()->isSame(null);

        $validate = new Date('Y-m-d\TH:i:s');
        that($validate)->getMin()->isSame('1000-01-01T00:00:00');
        $validate = new Date('Y-m-d\TH:i');
        that($validate)->getMin()->isSame('1000-01-01T00:00');
        $validate = new Date('Y-m-d');
        that($validate)->getMin()->isSame('1000-01-01');
        $validate = new Date('Y-m');
        that($validate)->getMin()->isSame('1000-01');
        $validate = new Date('Y');
        that($validate)->getMin()->isSame(null);
        $validate = new Date('H:i:s');
        that($validate)->getMin()->isSame(null);
    }

    function test_getMax()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getMax()->isSame(null);
        $validate = new Date('Y/m/d H:i');
        that($validate)->getMax()->isSame(null);
        $validate = new Date('Y/m/d');
        that($validate)->getMax()->isSame(null);
        $validate = new Date('Y/m');
        that($validate)->getMax()->isSame(null);
        $validate = new Date('Y');
        that($validate)->getMax()->isSame(null);

        $validate = new Date('Y-m-d\TH:i:s');
        that($validate)->getMax()->isSame('9999-12-31T23:59:59');
        $validate = new Date('Y-m-d\TH:i');
        that($validate)->getMax()->isSame('9999-12-31T23:59');
        $validate = new Date('Y-m-d');
        that($validate)->getMax()->isSame('9999-12-31');
        $validate = new Date('Y-m');
        that($validate)->getMax()->isSame('9999-12');
        $validate = new Date('Y');
        that($validate)->getMax()->isSame(null);
        $validate = new Date('H:i:s');
        that($validate)->getMax()->isSame(null);
    }

    function test_getStep()
    {
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y/m/d H:i');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y/m/d');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y/m');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y');
        that($validate)->getStep()->isSame(null);

        $validate = new Date('Y-m-d\TH:i:s');
        that($validate)->getStep()->isSame('1');
        $validate = new Date('Y-m-d\TH:i');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y-m-d');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y-m');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('Y');
        that($validate)->getStep()->isSame(null);
        $validate = new Date('H:i:s');
        that($validate)->getStep()->isSame('1');
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
        $validate = new Date('Y/m/d H:i:s');
        that($validate)->getType()->is("text");
        $validate = new Date('Y/m/d H:i');
        that($validate)->getType()->is("text");
        $validate = new Date('Y/m/d');
        that($validate)->getType()->is("text");
        $validate = new Date('Y/m');
        that($validate)->getType()->is("text");
        $validate = new Date('Y');
        that($validate)->getType()->is("text");

        $validate = new Date('Y-m-d\TH:i:s');
        that($validate)->getType()->is("datetime-local");
        $validate = new Date('Y-m-d\TH:i');
        that($validate)->getType()->is("datetime-local");
        $validate = new Date('Y-m-d');
        that($validate)->getType()->is("date");
        $validate = new Date('Y-m');
        that($validate)->getType()->is("month");
        $validate = new Date('Y');
        that($validate)->getType()->is('text');
        $validate = new Date('H:i:s');
        that($validate)->getType()->is("time");
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

    function test_getFixture()
    {
        $validate = new Date('Y-m-d\TH:i:s');
        $year11 = 60 * 60 * 24 * 365 * 11;
        that($validate)->getFixture(null, [])->isBetween(date('Y-m-d\TH:i:s', time() - $year11), date('Y-m-d\TH:i:s', time() + $year11));
    }
}
