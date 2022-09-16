<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Uri;

class UriTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Uri();

        that($validate)->isValid('http://hostname')->isTrue();
        that($validate)->isValid('hoge:///hostname')->isFalse();
        that($validate)->isValid('fuga:/hostname')->isFalse();
        that($validate)->isValid('fuga:hostname')->isFalse();
        that($validate)->isValid('hostname')->isFalse();
    }

    function test_scheme()
    {
        // 指定なし（全許可）
        $validate = new Uri([]);
        that($validate)->isValid('http://hostname')->isTrue();
        that($validate)->isValid('https://hostname')->isTrue();
        that($validate)->isValid('ftp://hostname')->isTrue();

        // http, https のみ
        $validate = new Uri([
            'http',
            'https'
        ]);
        that($validate)->isValid('http://hostname')->isTrue();
        that($validate)->isValid('https://hostname')->isTrue();
        that($validate)->isValid('ftp://hostname')->isFalse();

        // ftpのみ
        $validate = new Uri([
            'ftp'
        ]);
        that($validate)->isValid('http://hostname')->isFalse();
        that($validate)->isValid('https://hostname')->isFalse();
        that($validate)->isValid('ftp://hostname')->isTrue();
    }

    function test_getImeMode()
    {
        $validate = new Uri();
        that($validate)->getImeMode()->is(Uri::DISABLED);
    }

    function test_getType()
    {
        $validate = new Uri();
        that($validate)->getType()->is("url");
    }
}
