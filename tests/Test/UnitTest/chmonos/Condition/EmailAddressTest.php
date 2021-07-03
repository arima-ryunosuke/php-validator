<?php
namespace ryunosuke\Test\UnitTest\chmonos\Condition;

use ryunosuke\chmonos\Condition\EmailAddress;

class EmailAddressTest extends \ryunosuke\Test\AbstractUnitTestCase
{
    function test_valid()
    {
        $validate = new EmailAddress();
        $this->assertEquals(true, $validate->isValid('test@test.com'));
        $this->assertEquals(true, $validate->isValid('test@test'));
        $this->assertEquals(false, $validate->isValid('test@test,com'));
        $this->assertEquals(true, $validate->isValid('test.@test.com'));
        $this->assertEquals(false, $validate->isValid('test@.test.com'));
        $this->assertEquals(false, $validate->isValid('test.@.test.com'));

        $this->assertEquals(true, $validate->isValid('test@test.ne.jp'));
        $this->assertEquals(false, $validate->isValid('test@test,ne.jp'));
        $this->assertEquals(true, $validate->isValid('test.@test.ne.jp'));
        $this->assertEquals(false, $validate->isValid('test@.test.ne.jp'));
        $this->assertEquals(false, $validate->isValid('test.@.test.ne.jp'));

        $this->assertEquals(false, $validate->isValid('<test>@test.ne.jp'));

        $validate = new EmailAddress('#^aaa@bbb$#ui');
        $this->assertEquals(true, $validate->isValid('aaa@bbb'));
        $this->assertEquals(true, $validate->isValid('AAA@BBB'));
        $this->assertEquals(false, $validate->isValid('Xaaa@bbbX'));
    }

    function test_getImeMode()
    {
        $validate = new EmailAddress();
        $this->assertEquals(EmailAddress::DISABLED, $validate->getImeMode());
    }

    function test_getType()
    {
        $validate = new EmailAddress();
        $this->assertEquals('text', $validate->getType());
    }
}
