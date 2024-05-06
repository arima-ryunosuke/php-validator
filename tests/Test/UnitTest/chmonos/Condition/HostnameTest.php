<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\Hostname;

class HostnameTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_regular()
    {
        $validate = new Hostname(['']);
        that($validate)->isValid('127.0.0.1')->isFalse();
        that($validate)->isValid('::')->isFalse();
        that($validate)->isValid('example.com')->isTrue();
        that($validate)->isValid('localhost')->isTrue();
        that($validate)->isValid('example.com.')->isFalse();
        that($validate)->isValid('.localhost')->isFalse();
        that($validate)->isValid('.localhost.')->isFalse();
        that($validate)->isValid('exa_mple.com')->isFalse();
        that($validate)->isValid('1localhost')->isTrue();
        that($validate)->isValid('1example.com')->isTrue();
        that($validate)->isValid('example.1com')->isFalse();
        that($validate)->isValid('example.com.com.com')->isTrue();
        that($validate)->isValid('example.com.com.com.')->isFalse();

        that($validate)->isValid('a.b.c')->isTrue();
        that($validate)->isValid('1.2.c')->isTrue();
        that($validate)->isValid('2nd.example.com')->isTrue();
        that($validate)->isValid('999.example.com')->isTrue();
        that($validate)->isValid('1.2.0.192.in-addr.arpa')->isTrue();

        $validate = new Hostname(['', 'cidr', 4, 6]);
        that($validate)->isValid('127.0.0.1')->isTrue();
        that($validate)->isValid('127.0.0.1/24')->isTrue();
        that($validate)->isValid('::')->isTrue();
        that($validate)->isValid('example.com')->isTrue();
        that($validate)->isValid('localhost')->isTrue();

        $validate = new Hostname(['', 'cidr', 4, 6], null);
        that($validate)->isValid('127.0.0.1')->isTrue();
        that($validate)->isValid('127.0.0.1/24')->isTrue();
        that($validate)->isValid('example.com')->isTrue();
        that($validate)->isValid('localhost')->isTrue();
        that($validate)->isValid('127.0.0.1:80')->isTrue();
        that($validate)->isValid('127.0.0.1/24:80')->isTrue();
        that($validate)->isValid('example.com:80')->isTrue();
        that($validate)->isValid('localhost:80')->isTrue();
    }

    function test_cidr()
    {
        $validate = new Hostname(['cidr']);
        // 下記は false のはず
        that($validate)->isValid('127.0.0.1/')->isFalse();
        that($validate)->isValid('127.0.0.1/a')->isFalse();
        that($validate)->isValid('127.0.0.1/33')->isFalse();
        // 普通の ipv4 nocidr も受け付けない（受け付けたい場合は 4 を明示的に指定する）
        that($validate)->isValid('127.0.0.1')->isFalse();

        // 全サブネットでテスト
        foreach (range(0, 32) as $s) {
            that($validate)->isValid('127.0.0.1/' . $s)->isTrue();
        }

        $validate = new Hostname(['cidr'], true);
        that($validate)->isValid('127.0.0.1/16:80')->isTrue();
        that($validate)->isValid('127.0.0.1/16:65536')->isFalse();
        that($validate)->isValid('127.0.0.1/16:009')->isFalse();
        that($validate)->isValid('127.0.0.1/16')->isFalse();

        $validate = new Hostname(['cidr'], false);
        that($validate)->isValid('127.0.0.1/16:80')->isFalse();
        that($validate)->isValid('127.0.0.1/16:65536')->isFalse();
        that($validate)->isValid('127.0.0.1/16:009')->isFalse();
        that($validate)->isValid('127.0.0.1/16')->isTrue();

        $validate = new Hostname(['cidr'], null);
        that($validate)->isValid('127.0.0.1/16:80')->isTrue();
        that($validate)->isValid('127.0.0.1/16:65536')->isFalse();
        that($validate)->isValid('127.0.0.1/16:009')->isFalse();
        that($validate)->isValid('127.0.0.1/16')->isTrue();
    }

    function test_ipv4()
    {
        $validate = new Hostname([4]);
        that($validate)->isValid('127.0.0.1')->isTrue();
        that($validate)->isValid('::')->isFalse();
        that($validate)->isValid('example.com')->isFalse();
        that($validate)->isValid('localhost')->isFalse();

        $validate = new Hostname([4], true);
        that($validate)->isValid('127.0.0.1:80')->isTrue();
        that($validate)->isValid('127.0.0.1:65536')->isFalse();
        that($validate)->isValid('127.0.0.1:009')->isFalse();
        that($validate)->isValid('127.0.0.1')->isFalse();

        $validate = new Hostname([4], false);
        that($validate)->isValid('127.0.0.1:80')->isFalse();
        that($validate)->isValid('127.0.0.1:65536')->isFalse();
        that($validate)->isValid('127.0.0.1:009')->isFalse();
        that($validate)->isValid('127.0.0.1')->isTrue();

        $validate = new Hostname([4], null);
        that($validate)->isValid('127.0.0.1:80')->isTrue();
        that($validate)->isValid('127.0.0.1:65536')->isFalse();
        that($validate)->isValid('127.0.0.1:009')->isFalse();
        that($validate)->isValid('127.0.0.1')->isTrue();
    }

    function test_ipv6()
    {
        $validate = new Hostname([6]);
        that($validate)->isValid('127.0.0.1')->isFalse();
        that($validate)->isValid('::')->isTrue();
        that($validate)->isValid('example.com')->isFalse();
        that($validate)->isValid('localhost')->isFalse();
    }

    function test_multiple()
    {
        $validate = new Hostname(['', 4], false, '#,|\\s#');
        that($validate)->isValid('')->isTrue();
        that($validate)->isValid('example.com, 127.0.0.1,example.com')->isTrue();
        that($validate)->isValid('example.com, 127.0.0.1,example.com:80')->isFalse();
    }

    function test_getDelimiter()
    {
        $validate = new Hostname();
        that($validate)->getDelimiter()->is(null);

        $validate = new Hostname('', false, ',');
        that($validate)->getDelimiter()->is(',');
    }

    function test_getFixture()
    {
        $validate = new Hostname([''], null);
        that($validate)->getFixture(null, [])->isValidDomain();

        $validate = new Hostname(['4']);
        that($validate)->getFixture(null, [])->isValidIpv4();

        $validate = new Hostname(['6']);
        that($validate)->getFixture(null, [])->isValidIpv6();

        $validate = new Hostname(['6'], true);
        that($validate)->getFixture(null, [])->matches('#^\[[0-9a-z:]+\]:\d{1,5}$#');

        $validate = new Hostname(['cidr']);
        that($validate)->getFixture(null, [])->matches('#^192\.168\.\d+\.\d+/16$#');

        $validate = new Hostname(['cidr'], true);
        that($validate)->getFixture(null, [])->matches('#^192\.168\.\d+\.\d+/16:\d{1,5}$#');
    }
}
