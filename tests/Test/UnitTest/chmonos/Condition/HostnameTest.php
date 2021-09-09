<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Hostname;
use ryunosuke\chmonos\Condition\Interfaces;

class HostnameTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Hostname(['']);
        $this->assertEquals(false, $validate->isValid('127.0.0.1'));
        $this->assertEquals(false, $validate->isValid('::'));
        $this->assertEquals(true, $validate->isValid('example.com'));
        $this->assertEquals(true, $validate->isValid('localhost'));
        $this->assertEquals(false, $validate->isValid('example.com.'));
        $this->assertEquals(false, $validate->isValid('.localhost'));
        $this->assertEquals(false, $validate->isValid('.localhost.'));
        $this->assertEquals(false, $validate->isValid('exa_mple.com'));
        $this->assertEquals(true, $validate->isValid('1localhost'));
        $this->assertEquals(true, $validate->isValid('1example.com'));
        $this->assertEquals(false, $validate->isValid('example.1com'));
        $this->assertEquals(true, $validate->isValid('example.com.com.com'));
        $this->assertEquals(false, $validate->isValid('example.com.com.com.'));

        $this->assertEquals(true, $validate->isValid('a.b.c'));
        $this->assertEquals(true, $validate->isValid('1.2.c'));
        $this->assertEquals(true, $validate->isValid('2nd.example.com'));
        $this->assertEquals(true, $validate->isValid('999.example.com'));
        $this->assertEquals(true, $validate->isValid('1.2.0.192.in-addr.arpa'));

        $validate = new Hostname(['', 'cidr', 4, 6]);
        $this->assertEquals(true, $validate->isValid('127.0.0.1'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1/24'));
        $this->assertEquals(true, $validate->isValid('::'));
        $this->assertEquals(true, $validate->isValid('example.com'));
        $this->assertEquals(true, $validate->isValid('localhost'));

        $validate = new Hostname(['', 'cidr', 4, 6], null);
        $this->assertEquals(true, $validate->isValid('127.0.0.1'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1/24'));
        $this->assertEquals(true, $validate->isValid('example.com'));
        $this->assertEquals(true, $validate->isValid('localhost'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1:80'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1/24:80'));
        $this->assertEquals(true, $validate->isValid('example.com:80'));
        $this->assertEquals(true, $validate->isValid('localhost:80'));
    }

    function test_cidr()
    {
        $validate = new Hostname(['cidr']);
        // 下記は false のはず
        $this->assertEquals(false, $validate->isValid('127.0.0.1/'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/a'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/33'));
        // 普通の ipv4 nocidr も受け付けない（受け付けたい場合は 4 を明示的に指定する）
        $this->assertEquals(false, $validate->isValid('127.0.0.1'));

        // 全サブネットでテスト
        foreach (range(0, 32) as $s) {
            $this->assertEquals(true, $validate->isValid('127.0.0.1/' . $s));
        }

        $validate = new Hostname(['cidr'], true);
        $this->assertEquals(true, $validate->isValid('127.0.0.1/16:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:009'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16'));

        $validate = new Hostname(['cidr'], false);
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:009'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1/16'));

        $validate = new Hostname(['cidr'], null);
        $this->assertEquals(true, $validate->isValid('127.0.0.1/16:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1/16:009'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1/16'));
    }

    function test_ipv4()
    {
        $validate = new Hostname([4]);
        $this->assertEquals(true, $validate->isValid('127.0.0.1'));
        $this->assertEquals(false, $validate->isValid('::'));
        $this->assertEquals(false, $validate->isValid('example.com'));
        $this->assertEquals(false, $validate->isValid('localhost'));

        $validate = new Hostname([4], true);
        $this->assertEquals(true, $validate->isValid('127.0.0.1:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:009'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1'));

        $validate = new Hostname([4], false);
        $this->assertEquals(false, $validate->isValid('127.0.0.1:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:009'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1'));

        $validate = new Hostname([4], null);
        $this->assertEquals(true, $validate->isValid('127.0.0.1:80'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:65536'));
        $this->assertEquals(false, $validate->isValid('127.0.0.1:009'));
        $this->assertEquals(true, $validate->isValid('127.0.0.1'));
    }

    function test_ipv6()
    {
        $validate = new Hostname([6]);
        $this->assertEquals(false, $validate->isValid('127.0.0.1'));
        $this->assertEquals(true, $validate->isValid('::'));
        $this->assertEquals(false, $validate->isValid('example.com'));
        $this->assertEquals(false, $validate->isValid('localhost'));
    }

    function test_getImeMode()
    {
        $validate = new Hostname();
        $this->assertEquals(Interfaces\ImeMode::DISABLED, $validate->getImeMode());
    }
}
