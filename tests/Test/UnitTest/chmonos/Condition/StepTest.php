<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Step;

class StepTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test___construct()
    {
        that(Step::class)->new(0)->wasThrown(new \InvalidArgumentException('positive number'));
        that(Step::class)->new(-0.1)->wasThrown(new \InvalidArgumentException('positive number'));
    }

    function test_valid()
    {
        $validate = new Step(5);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(15)->isTrue();
        that($validate)->isValid(10)->isTrue();
        that($validate)->isValid(6)->isFalse();
        that($validate)->isValid(5)->isTrue();
        that($validate)->isValid(4)->isFalse();
        that($validate)->isValid(3)->isFalse();
        that($validate)->isValid(2)->isFalse();
        that($validate)->isValid(1)->isFalse();
        that($validate)->isValid(0.0)->isTrue();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(-0)->isTrue();
        that($validate)->isValid(-0.0)->isTrue();
        that($validate)->isValid(-1)->isFalse();
        that($validate)->isValid(-2)->isFalse();
        that($validate)->isValid(-3)->isFalse();
        that($validate)->isValid(-4)->isFalse();
        that($validate)->isValid(-5)->isTrue();
        that($validate)->isValid(-6)->isFalse();
        that($validate)->isValid(-10)->isTrue();
        that($validate)->isValid(-15)->isTrue();

        $validate = new Step(0.5);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(1.5)->isTrue();
        that($validate)->isValid(1.0)->isTrue();
        that($validate)->isValid(0.6)->isFalse();
        that($validate)->isValid(0.5)->isTrue();
        that($validate)->isValid(0.4)->isFalse();
        that($validate)->isValid(0.3)->isFalse();
        that($validate)->isValid(0.2)->isFalse();
        that($validate)->isValid(0.1)->isFalse();
        that($validate)->isValid(0.0)->isTrue();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(-0)->isTrue();
        that($validate)->isValid(-0.0)->isTrue();
        that($validate)->isValid(-0.1)->isFalse();
        that($validate)->isValid(-0.2)->isFalse();
        that($validate)->isValid(-0.3)->isFalse();
        that($validate)->isValid(-0.4)->isFalse();
        that($validate)->isValid(-0.5)->isTrue();
        that($validate)->isValid(-0.6)->isFalse();
        that($validate)->isValid(-1.0)->isTrue();
        that($validate)->isValid(-1.5)->isTrue();
        that($validate)->isValid(5)->isTrue();
        that($validate)->isValid(4)->isTrue();
        that($validate)->isValid(3)->isTrue();
        that($validate)->isValid(2)->isTrue();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid(-2)->isTrue();
        that($validate)->isValid(-3)->isTrue();
        that($validate)->isValid(-4)->isTrue();
        that($validate)->isValid(-5)->isTrue();

        $validate = new Step(0.005);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(0.015)->isTrue();
        that($validate)->isValid(0.010)->isTrue();
        that($validate)->isValid(0.006)->isFalse();
        that($validate)->isValid(0.005)->isTrue();
        that($validate)->isValid(0.004)->isFalse();
        that($validate)->isValid(0.003)->isFalse();
        that($validate)->isValid(0.002)->isFalse();
        that($validate)->isValid(0.001)->isFalse();
        that($validate)->isValid(0.0)->isTrue();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(-0)->isTrue();
        that($validate)->isValid(-0.0)->isTrue();
        that($validate)->isValid(-0.001)->isFalse();
        that($validate)->isValid(-0.002)->isFalse();
        that($validate)->isValid(-0.003)->isFalse();
        that($validate)->isValid(-0.004)->isFalse();
        that($validate)->isValid(-0.005)->isTrue();
        that($validate)->isValid(-0.006)->isFalse();
        that($validate)->isValid(-0.010)->isTrue();
        that($validate)->isValid(-0.015)->isTrue();
        that($validate)->isValid(5)->isTrue();
        that($validate)->isValid(4)->isTrue();
        that($validate)->isValid(3)->isTrue();
        that($validate)->isValid(2)->isTrue();
        that($validate)->isValid(1)->isTrue();
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid(-2)->isTrue();
        that($validate)->isValid(-3)->isTrue();
        that($validate)->isValid(-4)->isTrue();
        that($validate)->isValid(-5)->isTrue();

        $validate = new Step(0.4);
        that($validate)->isValid('invalid')->isFalse();
        that($validate)->isValid(1.2)->isTrue();
        that($validate)->isValid(0.8)->isTrue();
        that($validate)->isValid(0.6)->isFalse();
        that($validate)->isValid(0.5)->isFalse();
        that($validate)->isValid(0.4)->isTrue();
        that($validate)->isValid(0.3)->isFalse();
        that($validate)->isValid(0.2)->isFalse();
        that($validate)->isValid(0.1)->isFalse();
        that($validate)->isValid(0.0)->isTrue();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid(-0)->isTrue();
        that($validate)->isValid(-0.0)->isTrue();
        that($validate)->isValid(-0.1)->isFalse();
        that($validate)->isValid(-0.2)->isFalse();
        that($validate)->isValid(-0.3)->isFalse();
        that($validate)->isValid(-0.4)->isTrue();
        that($validate)->isValid(-0.5)->isFalse();
        that($validate)->isValid(-0.6)->isFalse();
        that($validate)->isValid(-0.8)->isTrue();
        that($validate)->isValid(-1.2)->isTrue();
        that($validate)->isValid(5)->isFalse();
        that($validate)->isValid(4)->isTrue();
        that($validate)->isValid(3)->isFalse();
        that($validate)->isValid(2)->isTrue();
        that($validate)->isValid(1)->isFalse();
        that($validate)->isValid(-1)->isFalse();
        that($validate)->isValid(-2)->isTrue();
        that($validate)->isValid(-3)->isFalse();
        that($validate)->isValid(-4)->isTrue();
        that($validate)->isValid(-5)->isFalse();
    }

    function test_getRange()
    {
        $validate = new Step(0.05);
        that($validate)->getMin()->isNull();
        that($validate)->getMax()->isNull();
        that($validate)->getStep()->is('0.05');
    }

    function test_getImeMode()
    {
        $validate = new Step(1);
        that($validate)->getImeMode()->is(Step::DISABLED);
    }

    function test_getType()
    {
        $validate = new Step(1);
        that($validate)->getType()->is("number");
    }
}
