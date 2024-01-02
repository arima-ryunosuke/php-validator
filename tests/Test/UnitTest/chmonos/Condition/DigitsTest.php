<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Digits;

class DigitsTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new Digits();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(0.9)->isFalse();
        that($validate)->isValid('0')->isTrue();
        that($validate)->isValid('0.9')->isFalse();
        that($validate)->isValid(001)->isTrue();
        that($validate)->isValid(0x2)->isTrue();
        that($validate)->isValid(-5)->isTrue();
        that($validate)->isValid('12e34')->isFalse();
    }

    function test_valid_sign()
    {
        $validate = new Digits('+');
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid('0')->isTrue();
        that($validate)->isValid('+5')->isTrue();
        that($validate)->isValid('-5')->isFalse();
        that($validate)->isValid(001)->isTrue();
        that($validate)->isValid(0x2)->isTrue();
        that($validate)->isValid('0x2')->isFalse();
        that($validate)->isValid(-5)->isFalse();

        $validate = new Digits('-');
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid('0')->isTrue();
        that($validate)->isValid('-5')->isTrue();
        that($validate)->isValid('+5')->isFalse();
        that($validate)->isValid(001)->isTrue();
        that($validate)->isValid(0x2)->isTrue();
        that($validate)->isValid('0x2')->isFalse();
        that($validate)->isValid(+5)->isTrue();
    }

    function test_valid_digit()
    {
        $validate = new Digits(null, 5);
        that($validate)->isValid(10001)->isTrue();
        that($validate)->isValid(-10001)->isTrue();
        that($validate)->isValid(+10001)->isTrue();
        that($validate)->isValid('00001')->isTrue();
        that($validate)->isValid('-00001')->isTrue();
        that($validate)->isValid('+00001')->isTrue();
        that($validate)->isValid(1001)->isFalse();
        that($validate)->isValid(-1001)->isFalse();
        that($validate)->isValid(+1001)->isFalse();
        that($validate)->isValid('0001')->isFalse();
        that($validate)->isValid('-0001')->isFalse();
        that($validate)->isValid('+0001')->isFalse();
        that($validate)->isValid(109901)->isFalse();
        that($validate)->isValid(-109901)->isFalse();
        that($validate)->isValid(+109901)->isFalse();
        that($validate)->isValid('009901')->isFalse();
        that($validate)->isValid('-009901')->isFalse();
        that($validate)->isValid('+009901')->isFalse();
    }

    function test_getMaxLength()
    {
        $validate = new Digits();
        that($validate)->getMaxLength()->is(null);

        $validate = new Digits('-', 5);
        that($validate)->getMaxLength()->is(6);
    }

    function test_getImeMode()
    {
        $validate = new Digits();
        that($validate)->getImeMode()->is(Digits::DISABLED);
    }

    function test_getType()
    {
        $validate = new Digits();
        that($validate)->getType()->is("number");
    }

    function test_getFixture()
    {
        $validate = new Digits();
        that($validate)->getFixture(null, [])->isBetween(-9999, 9999);

        $validate = new Digits('-', 3);
        that($validate)->getFixture(null, [])->isBetween(-999, 0);
    }
}
