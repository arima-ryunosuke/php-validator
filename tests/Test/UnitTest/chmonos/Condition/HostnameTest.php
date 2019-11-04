<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Hostname;
use ryunosuke\chmonos\Condition\Interfaces;

class HostnameTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Hostname(['']);
        $this->assertEquals($validate->isValid('127.0.0.1'), false);
        $this->assertEquals($validate->isValid('::'), false);
        $this->assertEquals($validate->isValid('example.com'), true);
        $this->assertEquals($validate->isValid('localhost'), true);
        $this->assertEquals($validate->isValid('example.com.'), false);
        $this->assertEquals($validate->isValid('.localhost'), false);
        $this->assertEquals($validate->isValid('.localhost.'), false);
        $this->assertEquals($validate->isValid('exa_mple.com'), false);
        $this->assertEquals($validate->isValid('1localhost'), true);
        $this->assertEquals($validate->isValid('1example.com'), true);
        $this->assertEquals($validate->isValid('example.1com'), false);
        $this->assertEquals($validate->isValid('example.com.com.com'), true);
        $this->assertEquals($validate->isValid('example.com.com.com.'), false);

        $this->assertEquals($validate->isValid('a.b.c'), true);
        $this->assertEquals($validate->isValid('1.2.c'), true);
        $this->assertEquals($validate->isValid('2nd.example.com'), true);
        $this->assertEquals($validate->isValid('999.example.com'), true);
        $this->assertEquals($validate->isValid('1.2.0.192.in-addr.arpa'), true);

        $validate = new Hostname(['', 'cidr', 4, 6]);
        $this->assertEquals($validate->isValid('127.0.0.1'), true);
        $this->assertEquals($validate->isValid('127.0.0.1/24'), true);
        $this->assertEquals($validate->isValid('::'), true);
        $this->assertEquals($validate->isValid('example.com'), true);
        $this->assertEquals($validate->isValid('localhost'), true);
    }

    function test_cidr()
    {
        $validate = new Hostname(['cidr']);
        // 下記は false のはず
        $this->assertEquals($validate->isValid('127.0.0.1/'), false);
        $this->assertEquals($validate->isValid('127.0.0.1/a'), false);
        $this->assertEquals($validate->isValid('127.0.0.1/33'), false);
        // 普通の ipv4 nocidr も受け付けない（受け付けたい場合は 4 を明示的に指定する）
        $this->assertEquals($validate->isValid('127.0.0.1'), false);

        // 全サブネットでテスト
        foreach (range(0, 32) as $s) {
            $this->assertEquals($validate->isValid('127.0.0.1/' . $s), true);
        }
    }

    function test_ipv4()
    {
        $validate = new Hostname([4]);
        $this->assertEquals($validate->isValid('127.0.0.1'), true);
        $this->assertEquals($validate->isValid('::'), false);
        $this->assertEquals($validate->isValid('example.com'), false);
        $this->assertEquals($validate->isValid('localhost'), false);
    }

    function test_ipv6()
    {
        $validate = new Hostname([6]);
        $this->assertEquals($validate->isValid('127.0.0.1'), false);
        $this->assertEquals($validate->isValid('::'), true);
        $this->assertEquals($validate->isValid('example.com'), false);
        $this->assertEquals($validate->isValid('localhost'), false);
    }

    function test_getImeMode()
    {
        $validate = new Hostname();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }
}
