<?php
namespace ryunosuke\Test\UnitTest\chmonos\Mixin;

use ryunosuke\chmonos\Mixin\Fixturable;

class FixturableTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    use Fixturable;

    function test_fixtureBool()
    {
        $list = [];
        for ($i = 0; $i < 4; $i++) {
            $list[] = $actual = $this->fixtureBool();
            that($actual)->isAny([false, true]);
        }
        that($list)->break()->containsAll([false, true]);
    }

    function test_fixtureInt()
    {
        $list = [];
        for ($i = 0; $i < 40; $i++) {
            $list[] = $actual = $this->fixtureInt(-9, 9);
            that($actual)->isBetween(-9, 9);
        }
        that($list)->break()->containsAll(range(-9, 9));

        for ($i = 0; $i < 40; $i++) {
            $actual = $this->fixtureInt(-70000, 70000);
            that($actual)->isBetween(-70000, 70000);
        }
    }

    function test_fixtureFloat()
    {
        for ($i = 0; $i < 40; $i++) {
            $actual = $this->fixtureFloat(-9.9, 9.9);
            that($actual)->isBetween(-9.9, 9.9);
        }

        $list = [];
        for ($i = 0; $i < 999; $i++) {
            $list[] = $this->fixtureFloat(-9.9, 9.9);
        }
        that(min($list))->break()->lt(-9.5);
        that(max($list))->break()->gt(+9.5);
    }

    function test_fixtureDecimal()
    {
        for ($i = 0; $i < 40; $i++) {
            $actual = $this->fixtureDecimal(0, 2);
            that($actual)->matches('#^-?0\.#')->isBetween(-1, 1);
        }
        for ($i = 0; $i < 40; $i++) {
            $actual = $this->fixtureDecimal(2, 0);
            that($actual)->matches('#^-?\d{1,2}$#')->isBetween(-99, 99);
        }
        for ($i = 0; $i < 40; $i++) {
            $actual = $this->fixtureDecimal(2, 3);
            that($actual)->isBetween(-99.999, 99.999);
        }

        $list = [];
        for ($i = 0; $i < 1000; $i++) {
            $list[] = $this->fixtureDecimal(2, 3);
        }
        that(min($list))->break()->lt(-99);
        that(max($list))->break()->gt(+99);
    }

    function test_fixtureString()
    {
        $list = [];
        for ($i = 0; $i < 12; $i++) {
            $list[] = $actual = $this->fixtureString(10, 'abcdef');
            that($actual)->stringLengthEquals(10);
        }
        that(implode("\n", $list))->break()->containsAll(str_split('abcdef'));
    }

    function test_fixtureArray()
    {
        $array = range(-9, 9);

        $list = [];
        for ($i = 0; $i < 40; $i++) {
            $list[] = $actual = $this->fixtureArray($array);
            that($actual)->isBetween(-9, 9);
        }
        that($list)->break()->containsAll(range(-9, 9));

        $list = [];
        for ($i = 0; $i < 40; $i++) {
            $list[] = $actual = $this->fixtureArray($array, 3);
            that($actual)->eachIsBetween(-9, 9);
        }
        that(array_merge(...$list))->break()->containsAll(range(-9, 9));
    }
}
