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
}
