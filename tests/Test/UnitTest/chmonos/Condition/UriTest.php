<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;
use ryunosuke\chmonos\Condition\Uri;

class UriTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Uri();

        $this->assertEquals($validate->isValid('http://hostname'), true);
        $this->assertEquals($validate->isValid('hoge:///hostname'), false);
        $this->assertEquals($validate->isValid('fuga:/hostname'), false);
        $this->assertEquals($validate->isValid('fuga:hostname'), false);
        $this->assertEquals($validate->isValid('hostname'), false);
    }

    function test_scheme()
    {
        // 指定なし（全許可）
        $validate = new Uri([]);
        $this->assertEquals($validate->isValid('http://hostname'), true);
        $this->assertEquals($validate->isValid('https://hostname'), true);
        $this->assertEquals($validate->isValid('ftp://hostname'), true);

        // http, https のみ
        $validate = new Uri([
            'http',
            'https'
        ]);
        $this->assertEquals($validate->isValid('http://hostname'), true);
        $this->assertEquals($validate->isValid('https://hostname'), true);
        $this->assertEquals($validate->isValid('ftp://hostname'), false);

        // ftpのみ
        $validate = new Uri([
            'ftp'
        ]);
        $this->assertEquals($validate->isValid('http://hostname'), false);
        $this->assertEquals($validate->isValid('https://hostname'), false);
        $this->assertEquals($validate->isValid('ftp://hostname'), true);
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
