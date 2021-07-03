<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Uri;

class UriTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Uri();

        $this->assertEquals(true, $validate->isValid('http://hostname'));
        $this->assertEquals(false, $validate->isValid('hoge:///hostname'));
        $this->assertEquals(false, $validate->isValid('fuga:/hostname'));
        $this->assertEquals(false, $validate->isValid('fuga:hostname'));
        $this->assertEquals(false, $validate->isValid('hostname'));
    }

    function test_scheme()
    {
        // 指定なし（全許可）
        $validate = new Uri([]);
        $this->assertEquals(true, $validate->isValid('http://hostname'));
        $this->assertEquals(true, $validate->isValid('https://hostname'));
        $this->assertEquals(true, $validate->isValid('ftp://hostname'));

        // http, https のみ
        $validate = new Uri([
            'http',
            'https'
        ]);
        $this->assertEquals(true, $validate->isValid('http://hostname'));
        $this->assertEquals(true, $validate->isValid('https://hostname'));
        $this->assertEquals(false, $validate->isValid('ftp://hostname'));

        // ftpのみ
        $validate = new Uri([
            'ftp'
        ]);
        $this->assertEquals(false, $validate->isValid('http://hostname'));
        $this->assertEquals(false, $validate->isValid('https://hostname'));
        $this->assertEquals(true, $validate->isValid('ftp://hostname'));
    }

    function test_getImeMode()
    {
        $validate = new Uri();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new Uri();
        $this->assertEquals('url', $validate->getType());
    }
}
