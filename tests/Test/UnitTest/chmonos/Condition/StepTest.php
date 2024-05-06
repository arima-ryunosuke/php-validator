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

        $validate = new Step(15, 'ja-jp');
        that($validate)->isValid("00:00:00")->isTrue();
        that($validate)->isValid("00:00:15")->isTrue();
        that($validate)->isValid("00:00:16")->isFalse();

        $validate = new Step(15 * 60, 'ja-jp');
        that($validate)->isValid("00:00:00")->isTrue();
        that($validate)->isValid("00:15:00")->isTrue();
        that($validate)->isValid("00:16:00")->isFalse();
        that($validate)->isValid("00:00")->isTrue();
        that($validate)->isValid("00:15")->isTrue();
        that($validate)->isValid("00:16")->isFalse();

        $validate = new Step(15 * 60 * 60, 'ja-jp');
        that($validate)->isValid("00:00:00")->isTrue();
        that($validate)->isValid("15:00:00")->isTrue();
        that($validate)->isValid("16:00:00")->isFalse();
        that($validate)->isValid("00:00")->isTrue();
        that($validate)->isValid("15:00")->isTrue();
        that($validate)->isValid("16:00")->isFalse();

        $validate = new Step(15 * 60 * 60, 'ja-jp');
        that($validate)->isValid("000000")->isTrue();
        that($validate)->isValid("150000")->isTrue();
        that($validate)->isValid("160000")->isFalse();
        that($validate)->isValid("hhmmss")->isFalse();
        that($validate)->isValid("0000")->isTrue();
        that($validate)->isValid("1500")->isTrue();
        that($validate)->isValid("1600")->isFalse();
        that($validate)->isValid("hhmm")->isFalse();

        $validate = new Step(15 * 60, ['i' => 'I', 's' => 'S']);
        that($validate)->isValid("000000")->isTrue();
        that($validate)->isValid("001500")->isTrue();
        that($validate)->isValid("001600")->isFalse();
        that($validate)->isValid("hhmmss")->isFalse();
        that($validate)->isValid("0000")->isTrue();
        that($validate)->isValid("1500")->isTrue();
        that($validate)->isValid("1600")->isFalse();
        that($validate)->isValid("mmss")->isFalse();
    }

    function test_getRange()
    {
        $validate = new Step(0.05);
        that($validate)->getMin()->isNull();
        that($validate)->getMax()->isNull();
        that($validate)->getStep()->is('0.05');
    }

    function test_getType()
    {
        $validate = new Step(1);
        that($validate)->getType()->is("number");
    }

    function test_getFixture()
    {
        $validate = new Step(0.7);
        that($validate)->getFixture(-3, [])->is('-2.8');
        that($validate)->getFixture(-2, [])->is('-1.4');
        that($validate)->getFixture(-1, [])->is('-0.7');
        that($validate)->getFixture(0, [])->is('0');
        that($validate)->getFixture(1, [])->is('0.7');
        that($validate)->getFixture(2, [])->is('1.4');
        that($validate)->getFixture(3, [])->is('2.8');

        $validate = new Step(3 * 3600, ['h' => 'H', 'i' => 'I', 's' => 'S']);
        that($validate)->getFixture(-3, [])->matches('#\d\d:\d\d:\d\d#');
        $validate = new Step(3 * 60, ['i' => 'I', 's' => 'S']);
        that($validate)->getFixture(-3, [])->matches('#\d\d:\d\d#');
        $validate = new Step(3 * 60, ['h' => 'H', 'i' => 'I']);
        that($validate)->getFixture(-3, [])->matches('#\d\d:\d\d#');
    }
}
