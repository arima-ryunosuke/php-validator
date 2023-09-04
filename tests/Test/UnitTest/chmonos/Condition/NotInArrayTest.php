<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\NotInArray;

class NotInArrayTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_scalar_value()
    {
        // strict. 型が違うと完全に弾かれてしまう
        $validate = new NotInArray(range(0, 100), true);
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid("-1")->isTrue();
        that($validate)->isValid(0)->isFalse();
        that($validate)->isValid("0")->isTrue();
        that($validate)->isValid(100)->isFalse();
        that($validate)->isValid("100")->isTrue();
        that($validate)->isValid(101)->isTrue();
        that($validate)->isValid("101")->isTrue();
        that($validate)->isValid("50str")->isTrue();
        that($validate)->getHaystack()->is(range(0, 100));

        // no strict. 緩すぎて 50str みたいな文字列も受け入れてしまう
        $validate = new NotInArray(range(0, 100), false);
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid("-1")->isTrue();
        that($validate)->isValid(0)->isFalse();
        that($validate)->isValid("0")->isFalse();
        that($validate)->isValid(100)->isFalse();
        that($validate)->isValid("100")->isFalse();
        that($validate)->isValid(101)->isTrue();
        that($validate)->isValid("101")->isTrue();
        that($validate)->getHaystack()->is(range(0, 100));

        // auto. 文字列化されて strict なのでいい感じにやってくれる
        $validate = new NotInArray(range(0, 100), null);
        that($validate)->isValid(-1)->isTrue();
        that($validate)->isValid("-1")->isTrue();
        that($validate)->isValid(0)->isFalse();
        that($validate)->isValid("0")->isFalse();
        that($validate)->isValid(100)->isFalse();
        that($validate)->isValid("100")->isFalse();
        that($validate)->isValid(101)->isTrue();
        that($validate)->isValid("101")->isTrue();
        that($validate)->isValid("50str")->isTrue();
        that($validate)->getHaystack()->is(range(0, 100));
    }
}
