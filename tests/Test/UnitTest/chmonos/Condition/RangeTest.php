<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Range;

class RangeTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_min()
    {
        $validate = new Range(1, null);

        that($validate)->isValid('0')->isFalse();
        that($validate)->isValid('1')->isTrue();
        that($validate)->isValid('999')->isTrue();
        that($validate)->isValid(0)->isFalse();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(999)->isTrue();
    }

    function test_max()
    {
        $validate = new Range(null, 999);

        that($validate)->isValid('-1')->isTrue();
        that($validate)->isValid('0')->isTrue();
        that($validate)->isValid('1')->isTrue();
        that($validate)->isValid('999')->isTrue();
        that($validate)->isValid('1000')->isFalse();
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(999)->isTrue();
        that($validate)->isValid(1000)->isFalse();
    }

    function test_minmax()
    {
        $validate = new Range(1, 999);

        that($validate)->isValid('-1')->isFalse();
        that($validate)->isValid('0')->isFalse();
        that($validate)->isValid('1')->isTrue();
        that($validate)->isValid('999')->isTrue();
        that($validate)->isValid('1000')->isFalse();
    }

    function test_getImeMode()
    {
        $validate = new Range(1, 999);
        that($validate)->getImeMode()->is(Range::DISABLED);
    }

    function test_getType()
    {
        $validate = new Range(1, 999);
        that($validate)->getType()->is("range");
    }

    function test_getRange()
    {
        $validate = new Range(-999, 999);

        that($validate)->getMin()->is(-999);
        that($validate)->getMax()->is(999);
        that($validate)->getStep()->isNull();
    }

    function test_getFixture()
    {
        $validate = new Range(-999, 999);
        that($validate)->getFixture(null, [])->isBetween(-999, 999);
    }
}
