<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Regex;

class RegexTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        //一致しないとダメ
        $validate = new Regex('/decimal:\d{3}\.\d{3}/', false);
        $this->assertEquals(true, $validate->isValid('decimal:123.456'));
        $this->assertEquals(false, $validate->isValid('hoge'));

        //一致したらダメ
        $validate = new Regex('/[<>]/', true);
        $this->assertEquals(false, $validate->isValid('<tag></tag>'));
        $this->assertEquals(true, $validate->isValid('plain'));
        $this->assertEquals(true, $validate->isValid('&lt;&gt;'));

        // 文字列的な物以外はダメ
        $validate = new Regex('/.*/');
        $this->assertEquals(false, $validate->isValid([null]));

        // マッチング自体が失敗
        $validate = new Regex('/(?:\D+|<\d+>)*[!?]/');
        $this->assertEquals(false, $validate->isValid('foobar foobar foobar'));
    }
}
