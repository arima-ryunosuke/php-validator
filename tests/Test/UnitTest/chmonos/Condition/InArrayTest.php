<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\InArray;

class InArrayTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_scalar_value()
    {
        // strict. 型が違うと完全に弾かれてしまう
        $validate = new InArray(range(0, 100), true);
        $this->assertEquals(false, $validate->isValid(-1));
        $this->assertEquals(false, $validate->isValid("-1"));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(false, $validate->isValid("0"));
        $this->assertEquals(true, $validate->isValid(100));
        $this->assertEquals(false, $validate->isValid("100"));
        $this->assertEquals(false, $validate->isValid(101));
        $this->assertEquals(false, $validate->isValid("101"));
        $this->assertEquals(false, $validate->isValid("50str"));

        // no strict. 緩すぎて 50str みたいな文字列も受け入れてしまう
        $validate = new InArray(range(0, 100), false);
        $this->assertEquals(false, $validate->isValid(-1));
        $this->assertEquals(false, $validate->isValid("-1"));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid("0"));
        $this->assertEquals(true, $validate->isValid(100));
        $this->assertEquals(true, $validate->isValid("100"));
        $this->assertEquals(false, $validate->isValid(101));
        $this->assertEquals(false, $validate->isValid("101"));
        $this->assertEquals(true, $validate->isValid("50str"));

        // auto. 文字列化されて strict なのでいい感じにやってくれる
        $validate = new InArray(range(0, 100), null);
        $this->assertEquals(false, $validate->isValid(-1));
        $this->assertEquals(false, $validate->isValid("-1"));
        $this->assertEquals(true, $validate->isValid(0));
        $this->assertEquals(true, $validate->isValid("0"));
        $this->assertEquals(true, $validate->isValid(100));
        $this->assertEquals(true, $validate->isValid("100"));
        $this->assertEquals(false, $validate->isValid(101));
        $this->assertEquals(false, $validate->isValid("101"));
        $this->assertEquals(false, $validate->isValid("50str"));
    }
}
