<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Decimal;

class DecimalTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Decimal(3, 3);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(12345.12345)->isFalse();

        $validate = new Decimal(5, 3);
        that($validate)->isValid(1.1)->isTrue();
        that($validate)->isValid(12345.123)->isTrue();
        that($validate)->isValid(123456.123)->isFalse();
        that($validate)->isValid(12345.1234)->isFalse();
        that($validate)->isValid(0.123)->isTrue();
        that($validate)->isValid(0.1234)->isFalse();
        that($validate)->isValid(12)->isTrue();
        that($validate)->isValid(123456)->isFalse();

        $validate = new Decimal(5, 0);
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(12345)->isTrue();
        that($validate)->isValid(12345.1)->isFalse();
        that($validate)->isValid(12345.12)->isFalse();
        that($validate)->isValid(12345.123)->isFalse();
        that($validate)->isValid(12345.1234)->isFalse();
        that($validate)->isValid(1.1)->isFalse();
        that($validate)->isValid(1.12)->isFalse();
        that($validate)->isValid(1.123)->isFalse();
        that($validate)->isValid(1.1234)->isFalse();
    }

    function test_getRange()
    {
        $validate = new Decimal(3, 4);
        that($validate)->getMin()->is("-999.9999");
        that($validate)->getMax()->is("999.9999");
        that($validate)->getStep()->is("0.0001");

        $validate = new Decimal(3, 0);
        that($validate)->getMin()->is("-999");
        that($validate)->getMax()->is("999");
        that($validate)->getStep()->is(1);
    }

    function test_getImeMode()
    {
        $validate = new Decimal(3, 4);
        that($validate)->getImeMode()->is(Decimal::DISABLED);
    }

    function test_getMaxLength()
    {
        $validate = new Decimal(3, 4);
        that($validate)->getMaxLength()->is(9);
        $validate = new Decimal(5, 6);
        that($validate)->getMaxLength()->is(13);
    }

    function test_getType()
    {
        $validate = new Decimal(1, 2);
        that($validate)->getType()->is("number");
    }
}
