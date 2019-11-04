<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\NotInArray;

class NotInArrayTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_scalar_value()
    {
        // strict. 型が違うと完全に弾かれてしまう
        $validate = new NotInArray(range(0, 100), true);
        $this->assertEquals($validate->isValid(-1), !false);
        $this->assertEquals($validate->isValid("-1"), !false);
        $this->assertEquals($validate->isValid(0), !true);
        $this->assertEquals($validate->isValid("0"), !false);
        $this->assertEquals($validate->isValid(100), !true);
        $this->assertEquals($validate->isValid("100"), !false);
        $this->assertEquals($validate->isValid(101), !false);
        $this->assertEquals($validate->isValid("101"), !false);
        $this->assertEquals($validate->isValid("50str"), !false);

        // no strict. 緩すぎて 50str みたいな文字列も受け入れてしまう
        $validate = new NotInArray(range(0, 100), false);
        $this->assertEquals($validate->isValid(-1), !false);
        $this->assertEquals($validate->isValid("-1"), !false);
        $this->assertEquals($validate->isValid(0), !true);
        $this->assertEquals($validate->isValid("0"), !true);
        $this->assertEquals($validate->isValid(100), !true);
        $this->assertEquals($validate->isValid("100"), !true);
        $this->assertEquals($validate->isValid(101), !false);
        $this->assertEquals($validate->isValid("101"), !false);
        $this->assertEquals($validate->isValid("50str"), !true);

        // auto. 文字列化されて strict なのでいい感じにやってくれる
        $validate = new NotInArray(range(0, 100), null);
        $this->assertEquals($validate->isValid(-1), !false);
        $this->assertEquals($validate->isValid("-1"), !false);
        $this->assertEquals($validate->isValid(0), !true);
        $this->assertEquals($validate->isValid("0"), !true);
        $this->assertEquals($validate->isValid(100), !true);
        $this->assertEquals($validate->isValid("100"), !true);
        $this->assertEquals($validate->isValid(101), !false);
        $this->assertEquals($validate->isValid("101"), !false);
        $this->assertEquals($validate->isValid("50str"), !false);
    }
}
