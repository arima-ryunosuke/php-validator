<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Number;

class NumberTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_validate()
    {
        $validate = new Number(-9.9, 9.9);

        that($validate)->isValid('-10.0')->isFalse();
        foreach (range(-9.9, 9.9, 0.1) as $number) {
            that($validate)->isValid((string) $number)->isTrue();
        }
        that($validate)->isValid('10.0')->isFalse();
    }

    function test_validate_intdec()
    {
        $validate = new Number(-123.456, 123.456);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(1234.5678)->isFalse();
        that($validate)->isValid(-123.456)->isTrue();
        that($validate)->isValid(123.456)->isTrue();

        $validate = new Number(0, 123.456);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(1234.5678)->isFalse();
        that($validate)->isValid(123.456)->isTrue();

        $validate = new Number(-100, 100);
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(-100)->isTrue();
        that($validate)->isValid(100)->isTrue();
        that($validate)->isValid(1.1)->isFalse();
        that($validate)->isValid(1.12)->isFalse();
        that($validate)->isValid(1.123)->isFalse();
        that($validate)->isValid(1.1234)->isFalse();
    }

    function test_validate_minmax()
    {
        $validate = new Number(1, 999);

        that($validate)->isValid('0')->isFalse();
        that($validate)->isValid('1')->isTrue();
        that($validate)->isValid('999')->isTrue();
        that($validate)->isValid('1000')->isFalse();
        that($validate)->isValid(0)->isFalse();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(999)->isTrue();
        that($validate)->isValid(1000)->isFalse();
    }

    function test_getMaxLength()
    {
        $validate = new Number(-999.999, 999.999);
        that($validate)->getMaxLength()->is(8);

        $validate = new Number(0, 999.999);
        that($validate)->getMaxLength()->is(7);

        $validate = new Number(-999.999, 0);
        that($validate)->getMaxLength()->is(8);

        $validate = new Number(0, 999);
        that($validate)->getMaxLength()->is(3);

        $validate = new Number(-999, 0);
        that($validate)->getMaxLength()->is(4);
    }

    function test_getImeMode()
    {
        $validate = new Number(1, 999);
        that($validate)->getImeMode()->is(Number::DISABLED);
    }

    function test_getType()
    {
        $validate = new Number(1, 999);
        that($validate)->getType()->is("number");
    }

    function test_getRange()
    {
        $validate = new Number(0, 999.999);
        that($validate)->getMin()->isSame('0');
        that($validate)->getMax()->isSame('999.999');
        that($validate)->getStep()->isSame('0.001');

        $validate = new Number(-999.999, 1000);
        that($validate)->getMin()->isSame('-999.999');
        that($validate)->getMax()->isSame('1000');
        that($validate)->getStep()->isSame('0.001');

        $validate = new Number(-999.9, 0);
        that($validate)->getMin()->isSame('-999.9');
        that($validate)->getMax()->isSame('0');
        that($validate)->getStep()->isSame('0.1');

        $validate = new Number(-999, 999);
        that($validate)->getMin()->isSame('-999');
        that($validate)->getMax()->isSame('999');
        that($validate)->getStep()->isSame('1');
    }

    function test_getFixture()
    {
        $validate = new Number("-9", "-8");
        that($validate)->getFixture(null, [])->isBetween(-9, -8);

        $validate = new Number("-9", "9");
        that($validate)->getFixture(null, [])->isBetween(-9.9, 9);

        $validate = new Number("-9.9", "-8.9");
        that($validate)->getFixture(null, [])->isBetween(-9.9, -8.9);

        $validate = new Number("-9.9", "9.9");
        that($validate)->getFixture(null, [])->isBetween(-9.9, 9.9);
    }
}
