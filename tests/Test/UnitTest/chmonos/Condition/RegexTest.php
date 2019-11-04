<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Regex;

class RegexTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        //一致しないとダメ
        $validate = new Regex('/decimal:\d{3}\.\d{3}/', false);
        $this->assertEquals($validate->isValid('decimal:123.456'), true);
        $this->assertEquals($validate->isValid('hoge'), false);

        //一致したらダメ
        $validate = new Regex('/[<>]/', true);
        $this->assertEquals($validate->isValid('<tag></tag>'), false);
        $this->assertEquals($validate->isValid('plain'), true);
        $this->assertEquals($validate->isValid('&lt;&gt;'), true);

        // 文字列的な物以外はダメ
        $validate = new Regex('/.*/');
        $this->assertEquals($validate->isValid([null]), false);

        // マッチング自体が失敗
        $validate = new Regex('/(?:\D+|<\d+>)*[!?]/');
        $this->assertEquals($validate->isValid('foobar foobar foobar'), false);
    }
}
