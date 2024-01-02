<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\InArray;

class InArrayTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_scalar_value()
    {
        // strict. 型が違うと完全に弾かれてしまう
        $validate = new InArray(range(0, 100), true);
        that($validate)->isValid(-1)->isFalse();
        that($validate)->isValid("-1")->isFalse();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid("0")->isFalse();
        that($validate)->isValid(100)->isTrue();
        that($validate)->isValid("100")->isFalse();
        that($validate)->isValid(101)->isFalse();
        that($validate)->isValid("101")->isFalse();
        that($validate)->isValid("50str")->isFalse();

        // no strict. 緩すぎて 50str みたいな文字列も受け入れてしまう
        $validate = new InArray(range(0, 100), false);
        that($validate)->isValid(-1)->isFalse();
        that($validate)->isValid("-1")->isFalse();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid("0")->isTrue();
        that($validate)->isValid(100)->isTrue();
        that($validate)->isValid("100")->isTrue();
        that($validate)->isValid(101)->isFalse();
        that($validate)->isValid("101")->isFalse();

        // auto. 文字列化されて strict なのでいい感じにやってくれる
        $validate = new InArray(range(0, 100), null);
        that($validate)->isValid(-1)->isFalse();
        that($validate)->isValid("-1")->isFalse();
        that($validate)->isValid(0)->isTrue();
        that($validate)->isValid("0")->isTrue();
        that($validate)->isValid(100)->isTrue();
        that($validate)->isValid("100")->isTrue();
        that($validate)->isValid(101)->isFalse();
        that($validate)->isValid("101")->isFalse();
        that($validate)->isValid("50str")->isFalse();
    }

    function test_getFixture()
    {
        $validate = new InArray(['', 2, 3], null);
        that($validate)->getFixture(null, [])->isAny([2, 3]);
        that($validate)->getFixture(1, [])->isAny([2, 3]);
        that($validate)->getFixture("2", [])->is(2);
        that($validate)->getFixture(3, [])->is(3);
        that($validate)->getFixture(4, [])->isAny([2, 3]);

        $validate = new InArray(['', 2, 3], true);
        that($validate)->getFixture(null, [])->isAny([2, 3]);
        that($validate)->getFixture(1, [])->isAny([2, 3]);
        that($validate)->getFixture("2", [])->is(2);
        that($validate)->getFixture(3, [])->is(3);
        that($validate)->getFixture(4, [])->isAny([2, 3]);
    }
}
