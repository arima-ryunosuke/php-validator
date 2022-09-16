<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Regex;

class RegexTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        //一致しないとダメ
        $validate = new Regex('/decimal:\d{3}\.\d{3}/', false);
        that($validate)->isValid('decimal:123.456')->isTrue();
        that($validate)->isValid('hoge')->isFalse();

        //一致したらダメ
        $validate = new Regex('/[<>]/', true);
        that($validate)->isValid('<tag></tag>')->isFalse();
        that($validate)->isValid('plain')->isTrue();
        that($validate)->isValid('&lt;&gt;')->isTrue();

        // 文字列的な物以外はダメ
        $validate = new Regex('/.*/');
        that($validate)->isValid([null])->isFalse();

        // マッチング自体が失敗
        $validate = new Regex('/(?:\D+|<\d+>)*[!?]/');
        that($validate)->isValid('foobar foobar foobar')->isFalse();
    }
}
